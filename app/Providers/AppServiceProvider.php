<?php

namespace App\Providers;

use App\Helpers\DateHelper;
use App\Models\Lpj;
use App\Modules\BOK\Models\LedgerEntry;
use App\Modules\BOK\Services\LedgerPostingService;
use App\Modules\BOK\Models\ReportMonthly;
use App\Modules\BOK\Services\NonParticipantPostingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-post BKU ketika LPJ dibuat (setelah commit), tanpa mengubah alur LPJ
        Event::listen('eloquent.created: ' . Lpj::class, function (Lpj $lpj) {
            DB::afterCommit(function () use ($lpj) {
                try {
                    $exists = LedgerEntry::query()
                        ->where('source_type', Lpj::class)
                        ->where('source_id', $lpj->id)
                        ->exists();
                    if ($exists) return;

                    $total = (float) $lpj->participants()->sum('total_amount');
                    if ($total <= 0) return;

                    $date = DateHelper::parseActivityDate((string) $lpj->tanggal_kegiatan) ?: $lpj->created_at;
                    $year = (int) $date->format('Y');
                    $month = (int) $date->format('n');
                    // Hormati periode yang dikunci
                    $locked = ReportMonthly::query()
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('status', 'LOCKED')
                        ->exists();
                    if ($locked) return; // jangan posting saat periode terkunci

                    $lastBalance = (float) (LedgerEntry::query()
                        ->where('period_year', $year)
                        ->where('period_month', $month)
                        ->orderByDesc('entry_date')
                        ->orderByDesc('id')
                        ->value('balance') ?? 0);

                    // Tentukan account_type dari config mapping transport_mode
                    $transport = (string) ($lpj->transport_mode ?? '');
                    $mapped = config('bok.ledger.lpj_account_by_transport.' . $transport);
                    $account = $mapped ?: config('bok.ledger.lpj_default_account', 'BANK');

                    app(LedgerPostingService::class)->postFromApproval($lpj, [
                        'entry_date' => $date->toDateString(),
                        'account_type' => strtoupper((string) $account) === 'CASH' ? 'CASH' : 'BANK',
                        'description' => 'LPJ ' . (string) $lpj->kegiatan,
                        'reference' => (string) $lpj->no_surat,
                        'debit' => 0,
                        'credit' => $total,
                        'period_year' => $year,
                        'period_month' => $month,
                        'balance' => $lastBalance - $total,
                    ]);

                    // Optional: auto-post biaya non-peserta hanya jika diaktifkan via config
                    if (config('bok.ledger.auto_post_non_participant', false)) {
                        $refNp = 'LPJ-NP-'.$lpj->id;
                        $existsNp = LedgerEntry::query()->where('reference', $refNp)->exists();
                        if (!$existsNp) {
                            $npAmount = app(NonParticipantPostingService::class)->computeForLpj($lpj);
                            if ($npAmount > 0.0) {
                                $locked2 = ReportMonthly::query()
                                    ->where('year', $year)
                                    ->where('month', $month)
                                    ->where('status', 'LOCKED')
                                    ->exists();
                                if (!$locked2) {
                                    $last = (float) (LedgerEntry::query()
                                        ->where('period_year', $year)
                                        ->where('period_month', $month)
                                        ->orderByDesc('entry_date')
                                        ->orderByDesc('id')
                                        ->value('balance') ?? 0);
                                    $accNp = config('bok.ledger.non_participant_account', 'CASH');
                                    app(LedgerPostingService::class)->postFromApproval($lpj, [
                                        'entry_date' => $date->toDateString(),
                                        'account_type' => strtoupper((string) $accNp) === 'BANK' ? 'BANK' : 'CASH',
                                        'description' => 'Belanja Operasional LPJ ' . (string) $lpj->kegiatan,
                                        'reference' => $refNp,
                                        'debit' => 0,
                                        'credit' => $npAmount,
                                        'period_year' => $year,
                                        'period_month' => $month,
                                        'balance' => $last - $npAmount,
                                    ]);
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Auto-post BKU LPJ (after-commit) gagal: ' . $e->getMessage(), [
                        'lpj_id' => $lpj->id ?? null,
                    ]);
                }
            });
        });
    }
}
