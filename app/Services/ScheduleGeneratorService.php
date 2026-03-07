<?php

namespace App\Services;

use App\Models\GlobalHoliday;
use App\Models\Lpj;
use Carbon\Carbon;

class ScheduleGeneratorService
{
    /**
     * Find the earliest available start date for a group of employees.
     *
     * @param array $employeeIds List of employee IDs involved in the activity
     * @param int $durationDays Duration of the activity in days
     * @param int $month Month (1-12)
     * @param int $year Year
     * @param array $existingBookings Array of existing bookings ['employee_id' => [Carbon date, ...]]
     * @param bool $allowParallel If true, ignores conflicts (use carefully)
     * @return Carbon|null Start date found or null
     */
    public function findAvailableDate(
        array $employeeIds,
        int $durationDays,
        int $month,
        int $year,
        array $existingBookings = [],
        bool $allowParallel = false
    ): ?Carbon {
        if ($allowParallel) {
            // If parallel allowed, just return the first working day of the month
            return $this->getFirstWorkingDay($month, $year);
        }

        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        // Iterate through each day of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);
            
            // Check if this date allows the full duration
            // (e.g., if duration is 3 days, we need day, day+1, day+2 to be valid)
            if ($day + $durationDays - 1 > $daysInMonth) {
                break; // Not enough days left in month
            }

            $isSlotAvailable = true;

            // Check specific duration slot
            for ($d = 0; $d < $durationDays; $d++) {
                $checkDate = $currentDate->copy()->addDays($d);
                
                // 1. Check Holidays / Weekends (Optional: strict rule?)
                // For now, let's assume Sundays are off, but we can configure this later.
                if ($checkDate->isSunday()) {
                    $isSlotAvailable = false;
                    break; 
                }

                // 2. Check Employee Conflicts
                if ($this->areEmployeesBusy($employeeIds, $checkDate, $existingBookings)) {
                    $isSlotAvailable = false;
                    break;
                }
            }

            if ($isSlotAvailable) {
                return $currentDate;
            }
        }

        return null; // No slot found
    }

    /**
     * Generate surat numbers for all LPJs in a given month.
     * 
     * @param int $month
     * @param int $year
     */
    public function generateSuratNumbers(int $month, int $year): void
    {
        $lpjs = Lpj::whereMonth('start_date', $month)
                   ->whereYear('start_date', $year)
                   ->orderBy('start_date', 'asc')
                   ->orderBy('created_at', 'asc')
                   ->get();

        $counter = 1;

        foreach ($lpjs as $lpj) {
            // Format no_surat as: TYPE/NNN/YEAR e.g., SPPT/001/2025
            $nomor = sprintf('%s/%s/%d', strtoupper((string) $lpj->type), str_pad($counter, 3, '0', STR_PAD_LEFT), (int) $year);

            // Persist to correct LPJ field
            $lpj->no_surat = $nomor;
            $lpj->saveQuietly(); // avoid triggering noisy events
            
            $counter++;
        }
    }

    protected function getFirstWorkingDay(int $month, int $year): Carbon
    {
        $date = Carbon::createFromDate($year, $month, 1);
        while ($date->isSunday()) {
            $date->addDay();
        }
        return $date;
    }

    protected function areEmployeesBusy(array $employeeIds, Carbon $date, array $existingBookings): bool
    {
        $dateStr = $date->toDateString();

        foreach ($employeeIds as $empId) {
            if (isset($existingBookings[$empId]) && in_array($dateStr, $existingBookings[$empId])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Build an index of existing bookings from DB or memory state.
     * @param mixed $lpjs Collection of existing LPJs or POAs
     * @return array ['emp_id' => ['2025-01-01', '2025-01-02']]
     */
    public function buildBookingIndex($lpjs): array
    {
        $index = [];

        foreach ($lpjs as $lpj) {
            // Calculate date range
            $start = Carbon::parse($lpj->start_date);
            $end = Carbon::parse($lpj->end_date);
            $participants = $lpj->participants()->pluck('employee_id')->toArray();

            $period = \Carbon\CarbonPeriod::create($start, $end);
            
            foreach ($participants as $empId) {
                if (!isset($index[$empId])) {
                    $index[$empId] = [];
                }
                foreach ($period as $dt) {
                    $index[$empId][] = $dt->toDateString();
                }
            }
        }

        return $index;
    }
}
