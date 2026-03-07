<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Poa;
use App\Models\Rab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MobileCalendarController extends Controller
{
    /**
     * Mobile login — returns Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Revoke old mobile tokens
        $user->tokens()->where('name', 'mobile-bok')->delete();

        $token = $user->createToken('mobile-bok')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role,
                'employee_id' => $user->employee_id,
            ],
        ]);
    }

    /**
     * Calendar grid — all POA activities for a year with monthly schedule.
     */
    public function calendar(Request $request)
    {
        $selectedYear = (int) $request->query('year', date('Y'));
        $availableYears = Poa::select('year')->distinct()->orderByDesc('year')->pluck('year')->toArray();
        if (empty($availableYears)) {
            $availableYears = [(int) date('Y')];
        }

        $poas = Poa::with(['rab', 'participants'])
            ->where('year', $selectedYear)
            ->orderBy('kegiatan')
            ->get();

        $currentEmployeeId = auth()->user()?->employee_id ? (int) auth()->user()->employee_id : null;

        $calendarData = [];
        foreach ($poas as $poa) {
            $schedule = $poa->schedule ?: ['months' => []];
            $months = $schedule['months'] ?? [];

            $generalParticipantIds = $poa->participants->pluck('employee_id')
                ->filter()->map(fn($id) => (int) $id)->unique()->values()->all();

            $row = [
                'id'           => $poa->id,
                'kegiatan'     => $poa->kegiatan,
                'komponen'     => $poa->rab?->komponen ?? '-',
                'rincian_menu' => $poa->rab?->rincian_menu ?? '-',
                'months'       => [],
            ];

            for ($m = 1; $m <= 12; $m++) {
                $meta = $months[$m] ?? [];
                $count  = (int) ($meta['count'] ?? 0);
                $marked = (bool) ($meta['marked'] ?? false);
                $claimed = ! empty($meta['claimed_at']) || ! empty($meta['sppt_lpj_id']) || ! empty($meta['sppd_lpj_id']);
                $hasActivity = $count > 0 || $marked || $claimed;

                $tahap = null;
                if ($hasActivity) {
                    if ($m >= 1 && $m <= 4)      $tahap = 1;
                    elseif ($m >= 5 && $m <= 8)  $tahap = 2;
                    else                          $tahap = 3;
                }

                $monthParticipantIds = collect($meta['participant_ids'] ?? [])->map(fn($id) => (int) $id)->all();
                $effectiveIds = ! empty($monthParticipantIds) ? $monthParticipantIds : $generalParticipantIds;
                $isMine = $currentEmployeeId && $hasActivity && ! empty($effectiveIds) && in_array($currentEmployeeId, $effectiveIds, true);

                $row['months'][$m] = [
                    'has_activity' => $hasActivity,
                    'count'        => $count,
                    'marked'       => $marked,
                    'claimed'      => $claimed,
                    'tahap'        => $tahap,
                    'is_mine'      => $isMine,
                ];
            }

            $calendarData[] = $row;
        }

        $availableKomponen = Rab::select('komponen')
            ->whereNotNull('komponen')
            ->distinct()
            ->orderBy('komponen')
            ->pluck('komponen')
            ->toArray();

        return response()->json([
            'year'               => $selectedYear,
            'available_years'    => $availableYears,
            'available_komponen' => $availableKomponen,
            'activities'         => $calendarData,
        ]);
    }

    /**
     * Calendar detail — specific POA month data.
     */
    public function calendarDetail(Poa $poa, int $month)
    {
        if ($month < 1 || $month > 12) {
            return response()->json(['error' => 'Bulan tidak valid'], 422);
        }

        $schedule = $poa->schedule ?: ['months' => []];
        $meta = $schedule['months'][$month] ?? [];

        // ── Participants ──
        $participantIds = collect($meta['participant_ids'] ?? [])
            ->map(fn($id) => (int) $id)->filter()->unique()->values()->all();

        $participants = [];
        $noParticipants = false;

        if (! empty($participantIds)) {
            $employees = Employee::whereIn('id', $participantIds)->pluck('nama', 'id');
            $poaParticipants = $poa->participants()->whereIn('employee_id', $participantIds)->get()->keyBy('employee_id');
            foreach ($participantIds as $eid) {
                $pp = $poaParticipants->get($eid);
                $participants[] = [
                    'nama' => $employees->get($eid, 'Pegawai #' . $eid),
                    'role' => $pp->role ?? null,
                ];
            }
        } else {
            $noParticipants = true;
        }

        // ── Schedules ──
        $schedules = [];

        $noSuratSppt         = $meta['no_surat_sppt'] ?? null;
        $tanggalSuratSppt    = $meta['tanggal_surat_sppt'] ?? null;
        $tanggalKegiatanSppt = $meta['tanggal_kegiatan_sppt'] ?? null;
        if ($noSuratSppt || $tanggalSuratSppt || $tanggalKegiatanSppt) {
            $schedules[] = [
                'type'             => 'SPPT',
                'nomor_surat'      => $noSuratSppt,
                'tanggal_surat'    => $tanggalSuratSppt,
                'tanggal_kegiatan' => $tanggalKegiatanSppt,
            ];
        }

        $noSuratSppd         = $meta['no_surat_sppd'] ?? null;
        $tanggalSuratSppd    = $meta['tanggal_surat_sppd'] ?? null;
        $tanggalKegiatanSppd = $meta['tanggal_kegiatan_sppd'] ?? null;
        if ($noSuratSppd || $tanggalSuratSppd || $tanggalKegiatanSppd) {
            $schedules[] = [
                'type'             => 'SPPD',
                'nomor_surat'      => $noSuratSppd,
                'tanggal_surat'    => $tanggalSuratSppd,
                'tanggal_kegiatan' => $tanggalKegiatanSppd,
            ];
        }

        // ── Budget ──
        $monthlyAmount = (float) ($meta['amount'] ?? 0);
        $plannedTotal  = (float) $poa->planned_total;
        $monthlyCount  = (int) ($meta['count'] ?? 0);

        // ── Status ──
        $claimed = ! empty($meta['claimed_at']) || ! empty($meta['sppt_lpj_id']) || ! empty($meta['sppd_lpj_id']);
        $marked  = (bool) ($meta['marked'] ?? false);

        $tahap = null;
        if ($month >= 1 && $month <= 4)      $tahap = 1;
        elseif ($month >= 5 && $month <= 8)  $tahap = 2;
        else                                  $tahap = 3;

        $monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return response()->json([
            'kegiatan'         => $poa->kegiatan,
            'komponen'         => $poa->rab?->komponen ?? '-',
            'bulan'            => $monthNames[$month],
            'tahun'            => $poa->year,
            'tahap'            => $tahap,
            'participants'     => $participants,
            'no_participants'  => $noParticipants,
            'schedules'        => $schedules,
            'estimated_budget' => $monthlyAmount,
            'planned_total'    => $plannedTotal,
            'monthly_count'    => $monthlyCount,
            'claimed'          => $claimed,
            'marked'           => $marked,
            // IDs for download buttons when already claimed
            'sppt_lpj_id'      => $meta['sppt_lpj_id'] ?? null,
            'sppd_lpj_id'      => $meta['sppd_lpj_id'] ?? null,
            'tiba_berangkat_id' => $this->findTibaBerangkatForMonth($poa, $month),
            'item_opsional'    => \App\Models\ItemOpsionalClaim::where('poa_id', $poa->id)
                ->where('month', $month)
                ->get(['id', 'label', 'type', 'amount'])
                ->toArray(),
        ]);
    }

    /**
     * Claim preparation — check eligibility.
     */
    public function claimPrep(Poa $poa, int $month)
    {
        if ($month < 1 || $month > 12) {
            return response()->json(['error' => 'Bulan tidak valid'], 422);
        }

        $user = auth()->user();
        $userIsAdmin = $user?->isSuperAdmin();
        $currentEmployeeId = $user?->employee_id;

        $schedule = $poa->schedule ?: ['months' => []];
        $meta = $schedule['months'][$month] ?? [];

        $alreadyClaimed = !empty($meta['claimed_at']) || !empty($meta['sppt_lpj_id']) || !empty($meta['sppd_lpj_id']);
        $locked = !empty($meta['locked']);

        $participantIds = collect($meta['participant_ids'] ?? [])
            ->map(fn($id) => (int) $id)->filter()->unique()->values()->all();
        $hasParticipants = !empty($participantIds);
        $isAssigned = $userIsAdmin || ($currentEmployeeId && in_array((int) $currentEmployeeId, $participantIds, true));

        $poa->loadMissing('rab.items');
        $targets = $this->computeRabVillageTargets($poa);
        $targetDarat = (int) ($targets['darat'] ?? 0);
        $targetSeberang = (int) ($targets['seberang'] ?? 0);

        $desaDarat = $meta['desa_tujuan_darat'] ?? null;
        $desaSeberang = $meta['desa_tujuan_seberang'] ?? null;

        if (!$desaDarat && $targetDarat === 2) {
            $desaDarat = 'Desa Kabalsiang dan Desa Benjuring';
        }
        if (!$desaSeberang && $targetSeberang === 3) {
            $desaSeberang = 'Desa Kumul, Desa Batuley dan Desa Kompane';
        }

        $canClaim = !$locked && $hasParticipants && $isAssigned;
        if ($locked && $userIsAdmin) $canClaim = $hasParticipants;
        $reason = null;
        if ($locked && !$userIsAdmin) $reason = 'Bulan ini dikunci oleh admin.';
        elseif (!$hasParticipants) $reason = 'Belum ada peserta yang ditugaskan.';
        elseif (!$isAssigned) $reason = 'Anda tidak termasuk peserta bulan ini.';

        return response()->json([
            'can_claim'          => $canClaim,
            'is_reclaim'         => $alreadyClaimed,
            'reason'             => $reason,
            'target_darat'       => $targetDarat,
            'target_seberang'    => $targetSeberang,
            'auto_desa_darat'    => $desaDarat,
            'auto_desa_seberang' => $desaSeberang,
        ]);
    }

    /**
     * Execute claim — delegates to PoaController::claim() which already supports JSON responses.
     */
    public function claim(Request $request, Poa $poa)
    {
        $poaController = app(\App\Http\Controllers\PoaController::class);

        // Force JSON response
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        return $poaController->claim($request, $poa);
    }

    /**
     * Download LPJ document (SPPT/SPPD) as .docx.
     */
    public function lpjDownload(\App\Models\Lpj $lpj)
    {
        $documentService = app(\App\Services\LpjDocumentService::class);

        try {
            $filePath = $documentService->generateDocument($lpj);
            $fullPath = storage_path('app/public/' . $filePath);

            if (!file_exists($fullPath)) {
                return response()->json(['error' => 'Dokumen tidak ditemukan.'], 404);
            }

            $downloadName = "{$lpj->type} {$lpj->no_surat} {$lpj->kegiatan}.docx";
            $downloadName = preg_replace('/[^A-Za-z0-9\-_.\s]/', '_', $downloadName);

            return response()->download($fullPath, $downloadName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membuat dokumen: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download Tiba Berangkat document as .docx.
     */
    public function tibaBerangkatDownload(\App\Models\TibaBerangkat $tibaBerangkat)
    {
        $tbController = app(\App\Http\Controllers\TibaBerangkatController::class);

        $request = request();
        return $tbController->download($request, $tibaBerangkat);
    }

    /**
     * Download Item Opsional (kwitansi) document as .docx.
     */
    public function itemOpsionalDownload(\App\Models\ItemOpsionalClaim $itemOpsionalClaim)
    {
        $controller = app(\App\Http\Controllers\ItemOpsionalController::class);

        $request = request();
        return $controller->download($request, $itemOpsionalClaim);
    }

    // ─── Helper methods ─────────────────────────────────────────────────────────

    private function computeRabVillageTargets(Poa $poa): array
    {
        $darat = 0;
        $seberang = 0;

        if (!$poa->rab) return compact('darat', 'seberang');

        foreach ($poa->rab->items ?? [] as $item) {
            $factors = is_array($item->factors) ? $item->factors : (json_decode($item->factors, true) ?? []);
            foreach ($factors as $factor) {
                $key = strtolower(trim($factor['key'] ?? $factor['label'] ?? ''));
                if (str_contains($key, 'darat') || str_contains($key, 'desa_darat')) {
                    $darat = max($darat, (int) ($factor['value'] ?? 0));
                }
                if (str_contains($key, 'seberang') || str_contains($key, 'laut')) {
                    $seberang = max($seberang, (int) ($factor['value'] ?? 0));
                }
            }
        }

        return compact('darat', 'seberang');
    }

    private function findTibaBerangkatForMonth(Poa $poa, int $month): ?int
    {
        $schedule = $poa->schedule ?: ['months' => []];
        $meta = $schedule['months'][$month] ?? [];

        $claimedAt = $meta['claimed_at'] ?? null;
        $claimedBy = $meta['claimed_by'] ?? null;

        if (!$claimedAt || !$claimedBy) return null;

        try {
            $from = \Carbon\Carbon::parse($claimedAt)->subMinutes(5);
            $to = \Carbon\Carbon::parse($claimedAt)->addMinutes(5);
            $tb = \App\Models\TibaBerangkat::where('created_by', $claimedBy)
                ->whereBetween('created_at', [$from, $to])
                ->first();
            return $tb?->id;
        } catch (\Throwable $e) {
            return null;
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  POA API
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * List POAs with search/filter/pagination.
     */
    public function poaList(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $month = (int) $request->query('month', 0);
        $selectedKomponen = trim((string) $request->query('komponen', ''));
        $year = (int) $request->query('year', date('Y'));
        $page = (int) $request->query('page', 1);

        $poas = Poa::with(['rab.items', 'participants.employee'])
            ->where('year', $year)
            ->when($q !== '', fn($query) => $query->where('kegiatan', 'like', "%$q%"))
            ->when($selectedKomponen !== '', fn($query) =>
                $query->whereHas('rab', fn($rq) => $rq->where('komponen', $selectedKomponen))
            )
            ->when($month >= 1 && $month <= 12, function ($query) use ($month) {
                $pathCount = "schedule->months->{$month}->count";
                $pathClaim = "schedule->months->{$month}->claimed_at";
                $pathMarked = "schedule->months->{$month}->marked";
                $query->where(function($q) use ($pathCount, $pathClaim, $pathMarked) {
                    $q->where($pathCount, '>', 0)
                      ->orWhereNotNull($pathClaim)
                      ->orWhere($pathMarked, true);
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'page', $page);

        $currentEmployeeId = auth()->user()?->employee_id ? (int) auth()->user()->employee_id : null;

        $items = $poas->getCollection()->map(function($poa) use ($currentEmployeeId) {
            $schedule = $poa->schedule ?: ['months' => []];
            $months = $schedule['months'] ?? [];

            $generalPids = $poa->participants->pluck('employee_id')
                ->filter()->map(fn($id) => (int) $id)->unique()->values()->all();

            $monthsData = [];
            for ($m = 1; $m <= 12; $m++) {
                $meta = $months[$m] ?? [];
                $count = (int) ($meta['count'] ?? 0);
                $marked = (bool) ($meta['marked'] ?? false);
                $claimed = !empty($meta['claimed_at']) || !empty($meta['sppt_lpj_id']) || !empty($meta['sppd_lpj_id']);
                $locked = (bool) ($meta['locked'] ?? false);
                $hasActivity = $count > 0 || $marked || $claimed;

                $monthPids = collect($meta['participant_ids'] ?? [])->map(fn($id) => (int) $id)->all();
                $effectiveIds = !empty($monthPids) ? $monthPids : $generalPids;
                $isMine = $currentEmployeeId && $hasActivity && in_array($currentEmployeeId, $effectiveIds, true);

                $monthsData[$m] = [
                    'has_activity' => $hasActivity,
                    'count' => $count,
                    'marked' => $marked,
                    'claimed' => $claimed,
                    'locked' => $locked,
                    'is_mine' => $isMine,
                    'tahap' => $hasActivity ? ($m <= 4 ? 1 : ($m <= 8 ? 2 : 3)) : null,
                ];
            }

            $participants = $poa->participants->map(fn($p) => [
                'employee_id' => $p->employee_id,
                'nama' => $p->employee?->nama ?? 'Pegawai #'.$p->employee_id,
                'role' => $p->role,
            ])->values()->all();

            return [
                'id' => $poa->id,
                'kegiatan' => $poa->kegiatan,
                'komponen' => $poa->rab?->komponen ?? '-',
                'rincian_menu' => $poa->rab?->rincian_menu ?? '-',
                'planned_total' => (float) $poa->planned_total,
                'year' => $poa->year,
                'months' => $monthsData,
                'participants' => $participants,
            ];
        });

        $availableKomponen = Rab::select('komponen')
            ->whereNotNull('komponen')->distinct()->orderBy('komponen')->pluck('komponen')->toArray();

        return response()->json([
            'data' => $items,
            'current_page' => $poas->currentPage(),
            'last_page' => $poas->lastPage(),
            'total' => $poas->total(),
            'available_komponen' => $availableKomponen,
            'available_years' => Poa::select('year')->distinct()->orderByDesc('year')->pluck('year')->toArray(),
        ]);
    }

    /**
     * POA Detail — full claim status, executed LPJs, item progress.
     */
    public function poaDetail(Poa $poa)
    {
        $poa->load(['rab.items', 'participants.employee']);

        $user = auth()->user();
        $userIsAdmin = $user && ($user->isSuperAdmin() || $user->email === 'admin@admin.com');
        $currentEmployeeId = $user?->employee_id;

        $schedule = $poa->schedule ?: ['months' => []];
        $monthMeta = $schedule['months'] ?? [];

        // Participants info
        $participants = $poa->participants->map(fn($p) => [
            'employee_id' => (int) $p->employee_id,
            'nama' => $p->employee?->nama ?? 'Pegawai #'.$p->employee_id,
            'role' => $p->role,
            'borrowed_employee_id' => $p->borrowed_employee_id,
        ])->values()->all();

        // Claim status per month
        $claimStatus = [];
        for ($m = 1; $m <= 12; $m++) {
            $meta = $monthMeta[$m] ?? [];
            $participantIds = collect($meta['participant_ids'] ?? [])
                ->map(fn($id) => (int) $id)->filter()->unique()->values()->all();
            $effectiveIds = $participantIds;
            if (empty($effectiveIds)) {
                $effectiveIds = $poa->participants->pluck('employee_id')
                    ->filter()->map(fn($id) => (int) $id)->unique()->values()->all();
            }
            $claimed = !empty($meta['claimed_at']) || !empty($meta['sppt_lpj_id']) || !empty($meta['sppd_lpj_id']);
            $locked = (bool) ($meta['locked'] ?? false);
            $assigned = $currentEmployeeId ? in_array((int) $currentEmployeeId, $effectiveIds, true) : false;

            $claimStatus[$m] = [
                'participant_ids' => $participantIds,
                'assigned' => $assigned,
                'claimed' => $claimed,
                'claimed_hidden' => (bool) ($meta['claimed_hidden'] ?? false),
                'locked' => $locked,
                'claimed_at' => $meta['claimed_at'] ?? null,
                'claimed_by' => $meta['claimed_by'] ?? null,
                'sppt_lpj_id' => $meta['sppt_lpj_id'] ?? null,
                'sppd_lpj_id' => $meta['sppd_lpj_id'] ?? null,
                'tiba_berangkat_id' => $this->findTibaBerangkatForMonth($poa, $m),
                'allowed' => ($userIsAdmin || $assigned) && !$claimed && (!$locked || $userIsAdmin),
                'no_surat_sppt' => $meta['no_surat_sppt'] ?? null,
                'no_surat_sppd' => $meta['no_surat_sppd'] ?? null,
                'tanggal_kegiatan_sppt' => $meta['tanggal_kegiatan_sppt'] ?? null,
                'tanggal_kegiatan_sppd' => $meta['tanggal_kegiatan_sppd'] ?? null,
                'tanggal_surat_sppt' => $meta['tanggal_surat_sppt'] ?? null,
                'tanggal_surat_sppd' => $meta['tanggal_surat_sppd'] ?? null,
                'holidays' => $meta['holidays'] ?? null,
                'darat_village_ids' => $meta['darat_village_ids'] ?? [],
                'seberang_village_ids' => $meta['seberang_village_ids'] ?? [],
                'desa_tujuan_darat' => $meta['desa_tujuan_darat'] ?? null,
                'desa_tujuan_seberang' => $meta['desa_tujuan_seberang'] ?? null,
                'borrowed_map' => $meta['borrowed_map'] ?? [],
                'marked' => (bool) ($meta['marked'] ?? false),
                'count' => (int) ($meta['count'] ?? 0),
                'amount' => (float) ($meta['amount'] ?? 0),
                'item_opsional' => \App\Models\ItemOpsionalClaim::where('poa_id', $poa->id)
                    ->where('month', $m)->get(['id', 'label', 'type', 'amount'])->toArray(),
            ];
        }

        // Claim users
        $claimUserIds = collect($claimStatus)->pluck('claimed_by')->filter()->unique()->values()->all();
        $claimUsers = !empty($claimUserIds) ? \App\Models\User::whereIn('id', $claimUserIds)->get()->keyBy('id') : collect();
        foreach ($claimStatus as $m => &$cs) {
            $cs['claimed_by_name'] = $cs['claimed_by'] ? ($claimUsers->get($cs['claimed_by'])?->name ?? null) : null;
        }
        unset($cs);

        // RAB targets
        $rabTargets = $this->computeRabVillageTargets($poa);

        // Item progress
        $itemProgress = $poa->item_progress ?: [];

        // RAB items for item progress UI
        $rabItems = $poa->rab?->items?->map(fn($item) => [
            'id' => $item->id,
            'label' => $item->label ?? $item->uraian ?? '',
            'type' => $item->type ?? null,
            'subtotal' => (float) ($item->subtotal ?? 0),
            'factors' => $item->factors,
        ])->values()->all() ?? [];

        // Villages (PejabatTtd)
        $villages = \App\Models\PejabatTtd::orderBy('desa')->get()->map(fn($p) => [
            'id' => $p->id,
            'nama' => $p->desa,
            'akses' => in_array($p->desa, ['Desa Kabalsiang', 'Desa Benjuring']) ? 'DARAT' : 'SEBERANG',
        ])->values()->all();

        return response()->json([
            'id' => $poa->id,
            'kegiatan' => $poa->kegiatan,
            'komponen' => $poa->rab?->komponen ?? '-',
            'rincian_menu' => $poa->rab?->rincian_menu ?? '-',
            'year' => $poa->year,
            'planned_total' => (float) $poa->planned_total,
            'nomor_surat' => $poa->nomor_surat,
            'output_target' => $poa->output_target,
            'rab_id' => $poa->rab_id,
            'participants' => $participants,
            'claim_status' => $claimStatus,
            'rab_targets' => $rabTargets,
            'item_progress' => $itemProgress,
            'rab_items' => $rabItems,
            'villages' => $villages,
            'is_admin' => $userIsAdmin,
        ]);
    }

    /**
     * Create POA.
     */
    public function poaStore(Request $request)
    {
        $this->adminGuard();

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'rab_id' => 'required|exists:rabs,id',
            'nomor_surat' => 'nullable|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'output_target' => 'nullable|string',
            'planned_total' => 'required|numeric|min:0',
            'schedule' => 'nullable|array',
            'participants' => 'nullable|array',
            'participants.*.employee_id' => 'required|exists:employees,id',
            'participants.*.role' => 'nullable|string|max:50',
        ]);

        // Check unique rab_id per year
        $exists = Poa::where('year', $validated['year'])->where('rab_id', $validated['rab_id'])->exists();
        if ($exists) return response()->json(['error' => 'RAB sudah digunakan untuk tahun ini.'], 422);

        $poa = \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $poa = Poa::create([
                'year' => $validated['year'],
                'rab_id' => $validated['rab_id'],
                'nomor_surat' => $validated['nomor_surat'] ?? null,
                'kegiatan' => $validated['kegiatan'],
                'output_target' => $validated['output_target'] ?? null,
                'schedule' => $validated['schedule'] ?? null,
                'planned_total' => (float) $validated['planned_total'],
                'created_by' => Auth::id(),
            ]);
            foreach (collect($validated['participants'] ?? [])->unique('employee_id')->values() as $p) {
                $poa->participants()->create([
                    'employee_id' => $p['employee_id'],
                    'role' => $p['role'] ?? null,
                ]);
            }
            return $poa;
        });

        return response()->json(['success' => true, 'id' => $poa->id, 'message' => 'POA berhasil dibuat.']);
    }

    /**
     * Update POA.
     */
    public function poaUpdate(Request $request, Poa $poa)
    {
        $this->adminGuard();

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'rab_id' => 'required|exists:rabs,id',
            'nomor_surat' => 'nullable|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'output_target' => 'nullable|string',
            'planned_total' => 'required|numeric|min:0',
            'participants' => 'nullable|array',
            'participants.*.employee_id' => 'required|exists:employees,id',
            'participants.*.role' => 'nullable|string|max:50',
        ]);

        $exists = Poa::where('year', $validated['year'])
            ->where('rab_id', $validated['rab_id'])
            ->where('id', '!=', $poa->id)
            ->exists();
        if ($exists) return response()->json(['error' => 'RAB sudah digunakan untuk tahun ini.'], 422);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $poa) {
            $poa->update([
                'year' => $validated['year'],
                'rab_id' => $validated['rab_id'],
                'nomor_surat' => $validated['nomor_surat'] ?? null,
                'kegiatan' => $validated['kegiatan'],
                'output_target' => $validated['output_target'] ?? null,
                'planned_total' => (float) $validated['planned_total'],
            ]);
            $poa->participants()->delete();
            foreach (collect($validated['participants'] ?? [])->unique('employee_id')->values() as $p) {
                $poa->participants()->create([
                    'employee_id' => $p['employee_id'],
                    'role' => $p['role'] ?? null,
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'POA berhasil diperbarui.']);
    }

    /**
     * Delete POA.
     */
    public function poaDestroy(Poa $poa)
    {
        $this->adminGuard();
        $poa->delete();
        return response()->json(['success' => true, 'message' => 'POA dihapus.']);
    }

    /**
     * Toggle mark month.
     */
    public function poaToggleMark(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate(['month' => 'required|integer|min:1|max:12', 'marked' => 'required|boolean']);
        $m = (int) $validated['month'];
        $schedule = $poa->schedule ?: ['months' => []];
        $schedule['months'][$m]['marked'] = (bool) $validated['marked'];
        $poa->schedule = $schedule;
        $poa->save();
        return response()->json(['ok' => true, 'month' => $m, 'marked' => (bool) $validated['marked']]);
    }

    /**
     * Toggle claim lock.
     */
    public function poaToggleClaimLock(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate(['month' => 'required|integer|min:1|max:12', 'locked' => 'required|boolean']);
        $m = (int) $validated['month'];
        $schedule = $poa->schedule ?: ['months' => []];
        if (!isset($schedule['months'][$m])) $schedule['months'][$m] = [];
        $schedule['months'][$m]['locked'] = (bool) $validated['locked'];
        $poa->schedule = $schedule;
        $poa->save();
        return response()->json(['ok' => true, 'month' => $m, 'locked' => (bool) $validated['locked']]);
    }

    /**
     * Toggle claim label visibility.
     */
    public function poaToggleClaimLabel(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate(['month' => 'required|integer|min:1|max:12', 'hidden' => 'required|boolean']);
        $m = (int) $validated['month'];
        $schedule = $poa->schedule ?: ['months' => []];
        if (!isset($schedule['months'][$m])) $schedule['months'][$m] = [];
        $schedule['months'][$m]['claimed_hidden'] = (bool) $validated['hidden'];
        $poa->schedule = $schedule;
        $poa->save();
        return response()->json(['ok' => true, 'month' => $m, 'hidden' => (bool) $validated['hidden']]);
    }

    /**
     * Upsert month metadata.
     */
    public function poaUpsertMonth(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'no_surat_sppt' => 'nullable|string|max:255',
            'no_surat_sppd' => 'nullable|string|max:255',
            'tanggal_kegiatan_sppt' => 'nullable|string|max:255',
            'tanggal_kegiatan_sppd' => 'nullable|string|max:255',
            'tanggal_surat_sppt' => 'nullable|string|max:255',
            'tanggal_surat_sppd' => 'nullable|string|max:255',
            'darat_village_ids' => 'nullable|string',
            'seberang_village_ids' => 'nullable|string',
            'participant_ids' => 'nullable|string',
            'holidays' => 'nullable|string',
            'borrowed_map' => 'nullable|string',
        ]);
        $m = (int) $validated['month'];

        $schedule = $poa->schedule ?: ['months' => []];
        $schedule['months'] = $schedule['months'] ?? [];
        $month = &$schedule['months'][$m];
        if (!is_array($month)) $month = [];

        // Update text fields
        foreach (['no_surat_sppt', 'no_surat_sppd', 'tanggal_kegiatan_sppt', 'tanggal_kegiatan_sppd', 'tanggal_surat_sppt', 'tanggal_surat_sppd'] as $field) {
            if (array_key_exists($field, $validated)) {
                $month[$field] = $validated[$field];
            }
        }

        // Village IDs
        foreach (['darat', 'seberang'] as $type) {
            $key = $type . '_village_ids';
            if (!empty($validated[$key])) {
                $ids = collect(explode(',', $validated[$key]))->map(fn($x) => (int) trim($x))->filter()->unique()->values()->all();
                $month[$key] = $ids;
            }
        }

        // Auto desa tujuan text
        foreach (['darat' => 'darat_village_ids', 'seberang' => 'seberang_village_ids'] as $type => $key) {
            $ids = $month[$key] ?? [];
            if (!empty($ids)) {
                $names = \App\Models\PejabatTtd::whereIn('id', $ids)->pluck('desa')->all();
                $month["desa_tujuan_{$type}"] = $this->formatDesaListMobile($names);
            } else {
                $month["desa_tujuan_{$type}"] = null;
            }
        }

        // Participant IDs
        if (array_key_exists('participant_ids', $validated)) {
            $ids = collect(explode(',', (string) $validated['participant_ids']))
                ->map(fn($x) => (int) trim($x))->filter()->unique()->values()->all();
            $month['participant_ids'] = !empty($ids) ? $ids : null;
        }

        // Holidays
        if (array_key_exists('holidays', $validated)) {
            $month['holidays'] = $validated['holidays'];
        }

        // Borrowed map
        if (array_key_exists('borrowed_map', $validated)) {
            $raw = trim((string) ($validated['borrowed_map'] ?? ''));
            $month['borrowed_map'] = $raw !== '' ? (json_decode($raw, true) ?: []) : [];
        }

        $poa->schedule = $schedule;
        $poa->save();
        return response()->json(['ok' => true, 'message' => 'Meta bulan disimpan.']);
    }

    /**
     * Update item progress.
     */
    public function poaItemProgress(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'rab_item_id' => 'required|integer',
            'completed' => 'required|boolean',
            'absorbed_amount' => 'nullable|numeric|min:0',
        ]);
        $poa->loadMissing('rab.items');
        $itemId = (int) $validated['rab_item_id'];
        $belongs = $poa->rab?->items?->firstWhere('id', $itemId);
        if (!$belongs) return response()->json(['error' => 'Item tidak ditemukan.'], 404);

        $progress = $poa->item_progress ?: [];
        $progress['months'] = $progress['months'] ?? [];
        $progress['months'][(int) $validated['month']]['items'][$itemId] = [
            'completed' => (bool) $validated['completed'],
            'absorbed_amount' => (float) ($validated['absorbed_amount'] ?? 0),
        ];
        $poa->item_progress = $progress;
        $poa->save();

        return response()->json(['ok' => true, 'message' => 'Item progress diperbarui.']);
    }

    /**
     * Carry over remaining budget.
     */
    public function poaCarryOver(Request $request, Poa $poa)
    {
        $this->adminGuard();
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'realized' => 'nullable|numeric|min:0',
        ]);
        $m = (int) $validated['month'];
        $schedule = $poa->schedule ?: ['months' => []];
        $months = $schedule['months'] ?? [];
        $amt = (float) ($months[$m]['amount'] ?? 0);
        $real = (float) ($validated['realized'] ?? 0);
        $delta = $amt - $real;
        if ($delta > 0) {
            $next = $m == 12 ? 12 : $m + 1;
            $months[$next]['amount'] = (float) (($months[$next]['amount'] ?? 0) + $delta);
            $months[$m]['amount'] = $real;
        }
        $schedule['months'] = $months;
        $poa->schedule = $schedule;
        $poa->save();
        return response()->json(['ok' => true, 'message' => 'Sisa anggaran dialihkan.']);
    }

    /**
     * Bulk lock/unlock all POAs for a month.
     */
    public function poaBulkLock(Request $request)
    {
        $this->adminGuard();
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'locked' => 'required|boolean',
        ]);
        $year = (int) $validated['year'];
        $m = (int) $validated['month'];
        $locked = (bool) $validated['locked'];
        $count = 0;
        Poa::where('year', $year)->orderBy('id')->chunkById(200, function ($batch) use ($m, $locked, &$count) {
            foreach ($batch as $poa) {
                $schedule = $poa->schedule ?: ['months' => []];
                $months = $schedule['months'] ?? [];
                if (!isset($months[$m])) $months[$m] = [];
                $months[$m]['locked'] = $locked;
                $schedule['months'] = $months;
                $poa->schedule = $schedule;
                $poa->save();
                $count++;
            }
        });
        return response()->json(['ok' => true, 'count' => $count, 'message' => ($locked ? 'Mengunci' : 'Membuka') . " klaim $count POA."]);
    }

    /**
     * Available RABs for POA creation.
     */
    public function poaAvailableRabs(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));
        $excludePoaId = (int) $request->query('exclude_poa_id', 0);

        $usedRabIds = Poa::where('year', $year)
            ->when($excludePoaId > 0, fn($q) => $q->where('id', '!=', $excludePoaId))
            ->pluck('rab_id');

        $rabs = Rab::whereNotIn('id', $usedRabIds)
            ->orderByDesc('created_at')
            ->limit(500)
            ->get(['id', 'komponen', 'rincian_menu', 'kegiatan', 'total']);

        return response()->json($rabs);
    }

    /**
     * List employees.
     */
    public function employeeList()
    {
        $employees = Employee::orderBy('nama')->get(['id', 'nama', 'nip', 'jabatan']);
        return response()->json($employees);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    private function adminGuard()
    {
        $user = auth()->user();
        if (!$user || (!$user->isSuperAdmin() && $user->email !== 'admin@admin.com')) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }
    }

    private function formatDesaListMobile(array $names): string
    {
        if (empty($names)) return '';
        if (count($names) === 1) return $names[0];
        $last = array_pop($names);
        return implode(', ', $names) . ' dan ' . $last;
    }
}
