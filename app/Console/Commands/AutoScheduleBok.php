<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ScheduleGeneratorService;
use App\Models\Lpj;
use App\Models\Poa;
use App\Models\GlobalHoliday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AutoScheduleBok extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bok:auto-schedule {month} {year} {--commit : Commit changes to DB}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Automatically generate schedule dates and surat numbers avoiding conflicts.';

    /**
     * Execute the console command.
     */
    public function handle(ScheduleGeneratorService $scheduler)
    {
        \Carbon\Carbon::setLocale('id'); // Force Indonesian for date names
        $month = $this->argument('month');
        $year = $this->argument('year');
        $commit = $this->option('commit');

        $this->info("Starting auto-schedule for $month/$year...");

        // Fetch LPJs without start_date in this month
        // Or fetch ALL to reschedule? For now, fetch ALL in month.
        $lpjs = Lpj::whereMonth('created_at', $month)
                   ->whereYear('created_at', $year)
                   ->get();

        if ($lpjs->isEmpty()) {
            $this->warn("No LPJs found created in this month period to schedule.");
            return;
        }

        $bookingIndex = [];
        
        DB::beginTransaction();

        foreach ($lpjs as $lpj) {
            // Determine Duration
            // Logic: if Seberang > 0 -> 2 days, else 1 day?
            // Or use RAB?
            // For now, let's use a heuristic based on village count or user hint.
            // "Transport seberang ada 3 desa ... Transport darat 2 desa"
            // Let's assume 1 day per village? Or 1 day total?
            // "1 pegawai tidak bisa ... 2 kegiatan sekaligus" implies multi-day is possible.
            // Let's default to: if total villages > 2 then 2 days, else 1 day.
            $totalDesa = ($lpj->jumlah_desa_darat ?? 0) + ($lpj->jumlah_desa_seberang ?? 0);
            $duration = $totalDesa > 2 ? 3 : 1; // Example heuristic
            
            // If explicit RAB link existed, we would use it.
            
            $rawParticipants = $lpj->participants->pluck('employee_id')->toArray();
            if (empty($rawParticipants)) {
                $this->warn("LPJ {$lpj->id} has no participants. Skipping.");
                continue;
            }

            $startDate = $scheduler->findAvailableDate(
                $rawParticipants,
                $duration,
                $month,
                $year,
                $bookingIndex
            );

            if ($startDate) {
                $endDate = $startDate->copy()->addDays($duration - 1);
                
                $this->line("Scheduled LPJ {$lpj->id} ($duration days): {$startDate->toDateString()} - {$endDate->toDateString()}");

                // Update Index
                $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                foreach ($rawParticipants as $empId) {
                    if (!isset($bookingIndex[$empId])) $bookingIndex[$empId] = [];
                    foreach ($period as $dt) {
                        $bookingIndex[$empId][] = $dt->toDateString();
                    }
                }

                // Stage Update
                $lpj->start_date = $startDate;
                $lpj->end_date = $endDate;
                
                // Format string like "01 s/d 03 Januari 2025" or "01 Januari 2025"
                if ($duration > 1) {
                    $lpj->tanggal_kegiatan = $startDate->format('d') . ' s/d ' . $endDate->translatedFormat('d F Y');
                } else {
                    $lpj->tanggal_kegiatan = $startDate->translatedFormat('d F Y'); // Requires proper locale
                }

                $lpj->save();
            } else {
                $this->error("Could not find slot for LPJ {$lpj->id} (Participants: " . implode(',', $rawParticipants) . ")");
            }
        }

        // After dates set, generate numbers
        $scheduler->generateSuratNumbers($month, $year);

        if ($commit) {
            DB::commit();
            $this->info("Schedule committed to database!");
        } else {
            DB::rollBack();
            $this->info("Dry run completed. No changes saved.");
        }
    }
}
