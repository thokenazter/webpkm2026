<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\Poa;
use App\Services\ScheduleGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityScheduleController extends Controller
{
    /**
     * Display the monthly schedule page.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get all schedules for this month/year with POA data
        $schedules = ActivitySchedule::with(['poa.rab', 'poa.participants.employee', 'createdByUser', 'finalizedByUser'])
            ->forMonth($month, $year)
            ->orderBy('start_date')
            ->orderBy('nomor_surat')
            ->get();

        // Get POAs that don't have schedule for this month yet
        $poasWithoutSchedule = Poa::where('year', $year)
            ->whereDoesntHave('schedules', function ($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            })
            ->with(['rab', 'participants.employee'])
            ->get();

        // Check if any schedule is finalized
        $hasFinalized = $schedules->where('status', 'finalized')->isNotEmpty();
        $hasDraft = $schedules->where('status', 'draft')->isNotEmpty();

        // Detect conflicts (same employee on overlapping dates)
        $conflicts = $this->detectConflicts($schedules);

        return view('activity-schedules.index', compact(
            'schedules',
            'poasWithoutSchedule',
            'month',
            'year',
            'hasFinalized',
            'hasDraft',
            'conflicts'
        ));
    }

    /**
     * Generate schedules automatically for a month.
     * Creates separate SPPT (darat) and SPPD (seberang) schedules based on RAB transport items.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Check if there are already finalized schedules
        $existingFinalized = ActivitySchedule::forMonth($month, $year)->finalized()->exists();
        if ($existingFinalized) {
            return back()->with('error', 'Tidak dapat generate ulang. Sudah ada jadwal yang dikunci untuk bulan ini.');
        }

        Carbon::setLocale('id');

        // Get all POAs for this year that need scheduling
        $poas = Poa::where('year', $year)
            ->with(['rab.items', 'participants.employee'])
            ->get();

        if ($poas->isEmpty()) {
            return back()->with('error', 'Tidak ada POA untuk tahun ini.');
        }

        $scheduler = new ScheduleGeneratorService();
        $bookingIndex = [];
        $generatedCount = 0;

        DB::transaction(function () use ($poas, $scheduler, $month, $year, &$bookingIndex, &$generatedCount) {
            // Delete existing drafts for this month
            ActivitySchedule::forMonth($month, $year)->draft()->delete();

            foreach ($poas as $poa) {
                // Check if POA has activity scheduled for this month
                $poaSchedule = $poa->schedule ?? [];
                $monthKey = (string) $month;
                $monthData = $poaSchedule['months'][$monthKey] ?? null;
                
                if (!$monthData || empty($monthData['count'])) {
                    continue;
                }

                // Get participants for this POA
                $participantIds = $poa->participants->pluck('employee_id')->toArray();
                if (!empty($monthData['participant_ids'])) {
                    $participantIds = $monthData['participant_ids'];
                }

                if (empty($participantIds)) {
                    continue;
                }

                // Analyze RAB items for transport types
                $transportInfo = $this->analyzeRabTransport($poa);
                
                // Generate SPPT schedule if darat exists
                if ($transportInfo['darat']['exists']) {
                    $duration = $transportInfo['darat']['desa_count'];
                    
                    $startDate = $scheduler->findAvailableDate(
                        $participantIds,
                        $duration,
                        $month,
                        $year,
                        $bookingIndex
                    );

                    if ($startDate) {
                        $endDate = $startDate->copy()->addDays($duration - 1);

                        ActivitySchedule::create([
                            'poa_id' => $poa->id,
                            'type' => 'SPPT',
                            'desa_count' => $transportInfo['darat']['desa_count'],
                            'month' => $month,
                            'year' => $year,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'status' => 'draft',
                            'created_by' => auth()->id(),
                        ]);

                        // Update booking index
                        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                        foreach ($participantIds as $empId) {
                            if (!isset($bookingIndex[$empId])) $bookingIndex[$empId] = [];
                            foreach ($period as $dt) {
                                $bookingIndex[$empId][] = $dt->toDateString();
                            }
                        }

                        $generatedCount++;
                    }
                }

                // Generate SPPD schedule if seberang exists (scheduled AFTER SPPT)
                if ($transportInfo['seberang']['exists']) {
                    $duration = $transportInfo['seberang']['desa_count'];
                    
                    $startDate = $scheduler->findAvailableDate(
                        $participantIds,
                        $duration,
                        $month,
                        $year,
                        $bookingIndex
                    );

                    if ($startDate) {
                        $endDate = $startDate->copy()->addDays($duration - 1);

                        ActivitySchedule::create([
                            'poa_id' => $poa->id,
                            'type' => 'SPPD',
                            'desa_count' => $transportInfo['seberang']['desa_count'],
                            'month' => $month,
                            'year' => $year,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'status' => 'draft',
                            'created_by' => auth()->id(),
                        ]);

                        // Update booking index
                        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                        foreach ($participantIds as $empId) {
                            if (!isset($bookingIndex[$empId])) $bookingIndex[$empId] = [];
                            foreach ($period as $dt) {
                                $bookingIndex[$empId][] = $dt->toDateString();
                            }
                        }

                        $generatedCount++;
                    }
                }
            }

            // Assign nomor surat based on date order (all types in one series)
            $this->assignNomorSurat($month, $year);
        });

        return back()->with('success', "Berhasil generate {$generatedCount} jadwal kegiatan.");
    }

    /**
     * Update a single schedule (inline edit).
     */
    public function update(Request $request, ActivitySchedule $schedule)
    {
        if (!$schedule->canBeEdited()) {
            return back()->with('error', 'Jadwal sudah dikunci dan tidak dapat diubah.');
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);

        // Re-assign nomor surat after date change
        $this->assignNomorSurat($schedule->month, $schedule->year);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Finalize (lock) all schedules for a month.
     */
    public function finalize(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Check for conflicts before finalizing
        $schedules = ActivitySchedule::with(['poa.participants'])
            ->forMonth($month, $year)
            ->draft()
            ->get();

        if ($schedules->isEmpty()) {
            return back()->with('error', 'Tidak ada jadwal draft untuk dikunci.');
        }

        $conflicts = $this->detectConflicts($schedules);
        if ($conflicts->isNotEmpty()) {
            return back()->with('error', 'Tidak dapat mengunci jadwal. Masih ada bentrok tanggal untuk pegawai yang sama. Silakan perbaiki terlebih dahulu.');
        }

        DB::transaction(function () use ($schedules) {
            foreach ($schedules as $schedule) {
                $schedule->markAsFinalized(auth()->id());
            }
        });

        return back()->with('success', 'Semua jadwal bulan ini berhasil dikunci.');
    }

    /**
     * Reset (delete) all draft schedules for a month.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $deleted = ActivitySchedule::forMonth($request->month, $request->year)
            ->draft()
            ->delete();

        return back()->with('success', "Berhasil menghapus {$deleted} jadwal draft.");
    }

    /**
     * Unlock (revert to draft) all finalized schedules for a month.
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Check if any schedule has been claimed (used in LPJ) - cannot unlock those
        $hasClaimed = ActivitySchedule::forMonth($month, $year)->claimed()->exists();
        if ($hasClaimed) {
            return back()->with('error', 'Tidak dapat membuka kunci. Beberapa jadwal sudah diklaim untuk LPJ.');
        }

        $unlocked = ActivitySchedule::forMonth($month, $year)
            ->finalized()
            ->update([
                'status' => 'draft',
                'finalized_at' => null,
                'finalized_by' => null,
            ]);

        return back()->with('success', "Berhasil membuka kunci {$unlocked} jadwal. Sekarang bisa digenerate ulang.");
    }

    // ==================== Helper Methods ====================

    /**
     * Analyze RAB items to find transport types and village counts.
     * Returns info about darat (SPPT) and seberang (SPPD) transport.
     */
    private function analyzeRabTransport(Poa $poa): array
    {
        $result = [
            'darat' => ['exists' => false, 'desa_count' => 0],
            'seberang' => ['exists' => false, 'desa_count' => 0],
        ];

        if (!$poa->rab || !$poa->rab->items) {
            return $result;
        }

        foreach ($poa->rab->items as $item) {
            $label = strtolower($item->label ?? '');
            $factors = $item->factors ?? [];
            
            // Find Desa count from factors
            $desaCount = 1;
            foreach ($factors as $factor) {
                if (isset($factor['label']) && strtolower($factor['label']) === 'desa') {
                    $desaCount = max(1, (int) ($factor['value'] ?? 1));
                    break;
                }
            }

            // Check for Transport Darat
            if (str_contains($label, 'transport') && str_contains($label, 'darat')) {
                $result['darat']['exists'] = true;
                $result['darat']['desa_count'] = max($result['darat']['desa_count'], $desaCount);
            }
            
            // Check for Transport Laut/Seberang
            if (str_contains($label, 'transport') && 
                (str_contains($label, 'laut') || str_contains($label, 'seberang'))) {
                $result['seberang']['exists'] = true;
                $result['seberang']['desa_count'] = max($result['seberang']['desa_count'], $desaCount);
            }
        }

        return $result;
    }

    /**
     * Determine activity duration from RAB or use heuristic (legacy, keeping for reference).
     */
    private function determineDuration(Poa $poa): int
    {
        $transport = $this->analyzeRabTransport($poa);
        $totalDesa = $transport['darat']['desa_count'] + $transport['seberang']['desa_count'];
        return max(1, $totalDesa);
    }

    /**
     * Assign nomor surat based on start_date order.
     */
    private function assignNomorSurat(int $month, int $year): void
    {
        $schedules = ActivitySchedule::forMonth($month, $year)
            ->orderBy('start_date', 'asc')
            ->orderBy('id', 'asc') // Secondary sort for same date
            ->get();

        $counter = 1;
        foreach ($schedules as $schedule) {
            $schedule->nomor_surat = str_pad($counter, 3, '0', STR_PAD_LEFT);
            $schedule->saveQuietly();
            $counter++;
        }
    }

    /**
     * Detect conflicts (same employee on overlapping dates).
     */
    private function detectConflicts($schedules)
    {
        $conflicts = collect();
        $employeeDateMap = [];

        foreach ($schedules as $schedule) {
            $participants = $schedule->poa->participants ?? collect();
            $period = \Carbon\CarbonPeriod::create($schedule->start_date, $schedule->end_date);

            foreach ($participants as $participant) {
                $empId = $participant->employee_id;
                $empName = $participant->employee->nama ?? "ID: {$empId}";

                foreach ($period as $date) {
                    $dateStr = $date->toDateString();
                    $key = "{$empId}_{$dateStr}";

                    if (isset($employeeDateMap[$key])) {
                        $conflicts->push([
                            'employee' => $empName,
                            'date' => $dateStr,
                            'schedule_1' => $employeeDateMap[$key],
                            'schedule_2' => $schedule->id,
                        ]);
                    } else {
                        $employeeDateMap[$key] = $schedule->id;
                    }
                }
            }
        }

        return $conflicts;
    }
}
