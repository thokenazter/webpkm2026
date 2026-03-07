<?php

namespace App\Console\Commands;

use App\Models\Lpj;
use App\Modules\BOK\Models\LedgerEntry;
use App\Modules\BOK\Services\NonParticipantPostingService;
use Illuminate\Console\Attributes\AsCommand;
use Illuminate\Console\Command;

#[AsCommand(name: 'bok:ledger:recalc')]
class BokLedgerRecalcCommand extends Command
{
    protected $signature = 'bok:ledger:recalc {--year=} {--month=} {--lpj_id=} {--dry-run} {--create-missing}';
    protected $description = 'Recalculate BKU (ledger) non-participant postings using current formula, and rebuild balances per period';

    public function handle()
    {
        $year = $this->option('year');
        $month = $this->option('month');
        $lpjId = $this->option('lpj_id');
        $dry = (bool) $this->option('dry-run');
        $createMissing = (bool) $this->option('create-missing');

        $targetLpjIds = [];
        if (!empty($lpjId)) {
            $lpj = Lpj::find((int) $lpjId);
            if (!$lpj) {
                $this->error("LPJ #{$lpjId} not found");
                return self::FAILURE;
            }
            $targetLpjIds = [(int) $lpj->id];
        } else {
            $q = LedgerEntry::query()->where('reference', 'like', 'LPJ-NP-%');
            if (!empty($year)) $q->where('period_year', (int) $year);
            if (!empty($month)) $q->where('period_month', (int) $month);
            $refs = $q->pluck('reference');
            foreach ($refs as $ref) {
                if (preg_match('/^LPJ-NP-(\d+)$/', (string) $ref, $m)) {
                    $targetLpjIds[] = (int) $m[1];
                }
            }
            $targetLpjIds = array_values(array_unique(array_filter($targetLpjIds)));
        }

        if (empty($targetLpjIds)) {
            $this->info('No target LPJ found for recalculation.');
            return self::SUCCESS;
        }

        $svc = app(NonParticipantPostingService::class);
        $affectedPeriods = [];
        $updated = 0; $deleted = 0; $skipped = 0;

        foreach ($targetLpjIds as $id) {
            $lpj = Lpj::find($id);
            if (!$lpj) { $skipped++; continue; }

            $expected = (float) $svc->computeForLpj($lpj);
            $entry = LedgerEntry::query()->where('reference', 'LPJ-NP-'.$lpj->id)->first();
            if (!$entry) {
                if ($expected > 0) {
                    if ($dry || !$createMissing) {
                        $this->line("" . ($dry ? '[DRY] ' : '') . "LPJ #{$lpj->id}: expected {$expected}, but no LPJ-NP entry found" . ($createMissing ? ' (will create)' : ' (skipped)'));
                        if (!$createMissing) { $skipped++; continue; }
                        if ($dry) { continue; }
                    }
                    // Create missing NP entry
                    $date = \App\Helpers\DateHelper::parseActivityDate((string) $lpj->tanggal_kegiatan) ?: $lpj->created_at;
                    $year = (int) $date->format('Y');
                    $month = (int) $date->format('n');
                    $last = (float) (\App\Modules\BOK\Models\LedgerEntry::query()
                        ->where('period_year', $year)
                        ->where('period_month', $month)
                        ->orderByDesc('entry_date')
                        ->orderByDesc('id')
                        ->value('balance') ?? 0);
                    $acc = strtoupper((string) config('bok.ledger.non_participant_account', 'CASH')) === 'BANK' ? 'BANK' : 'CASH';
                    \App\Modules\BOK\Models\LedgerEntry::create([
                        'entry_date' => $date->toDateString(),
                        'account_type' => $acc,
                        'description' => 'Belanja Operasional LPJ ' . (string) $lpj->kegiatan,
                        'reference' => 'LPJ-NP-'.$lpj->id,
                        'debit' => 0,
                        'credit' => $expected,
                        'balance' => $last - $expected,
                        'period_year' => $year,
                        'period_month' => $month,
                        'posted_at' => now(),
                        'source_type' => Lpj::class,
                        'source_id' => $lpj->id,
                    ]);
                    $updated++;
                    $affectedPeriods[$year.':'.$month] = [$year, $month];
                }
                $skipped++;
                continue;
            }

            $old = (float) $entry->credit;
            $periodKey = $entry->period_year.':'.$entry->period_month;

            if (abs($expected - $old) < 0.5) { // ignore minor cents diff
                $skipped++;
                continue;
            }

            if ($dry) {
                $this->line("[DRY] LPJ #{$lpj->id} period {$periodKey}: credit {$old} -> {$expected}");
                continue;
            }

            if ($expected <= 0) {
                $entry->delete();
                $deleted++;
                $affectedPeriods[$periodKey] = [$entry->period_year, $entry->period_month];
                continue;
            }

            $entry->credit = $expected;
            $entry->account_type = strtoupper((string) config('bok.ledger.non_participant_account', 'CASH')) === 'BANK' ? 'BANK' : 'CASH';
            $entry->save();
            $updated++;
            $affectedPeriods[$periodKey] = [$entry->period_year, $entry->period_month];
        }

        // Rebuild balances for affected periods
        if (!$dry && !empty($affectedPeriods)) {
            foreach ($affectedPeriods as $pk => [$y,$m]) {
                $this->rebuildPeriodBalance((int)$y, (int)$m);
                $this->line("Rebuilt balances for period {$y}-" . str_pad($m,2,'0',STR_PAD_LEFT));
            }
        }

        $this->info("Done. Updated: {$updated}, Deleted: {$deleted}, Skipped: {$skipped}");
        return self::SUCCESS;
    }

    private function rebuildPeriodBalance(int $year, int $month): void
    {
        $entries = LedgerEntry::query()
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();
        if ($entries->isEmpty()) return;

        // Determine baseline: opening entry if exists; otherwise anchor to earliest current balance
        $opening = $entries->firstWhere('reference', sprintf('OPENING-%04d-%02d', $year, $month));
        if ($opening) {
            $prev = (float) $opening->balance;
        } else {
            $first = $entries->first();
            $prev = (float) $first->balance - ((float)$first->debit - (float)$first->credit);
        }

        foreach ($entries as $row) {
            if ($opening && $row->id === $opening->id) {
                // ensure opening balance is correct
                if ((float)$row->balance !== $prev) {
                    $row->balance = $prev; $row->save();
                }
                continue;
            }
            $prev = $prev + (float)$row->debit - (float)$row->credit;
            if (abs((float)$row->balance - $prev) > 0.01) {
                $row->balance = $prev; $row->save();
            }
        }
    }
}
