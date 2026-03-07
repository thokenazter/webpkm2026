<?php

namespace App\Modules\BOK\Services;

use App\Modules\BOK\Models\ReportMonthly;
use App\Modules\BOK\Models\ReportAnnual;

class ReportGeneratorService
{
    /** Build monthly realization report from ledger (stub). */
    public function generateMonthly(int $year, int $month): ReportMonthly
    {
        $report = ReportMonthly::firstOrNew(['year' => $year, 'month' => $month]);
        $report->status = $report->status ?: 'OPEN';
        $report->totals = $report->totals ?: ['income' => 0, 'expense' => 0];
        $report->generated_at = now();
        $report->save();
        return $report;
    }

    /** Build annual LPJ compilation (stub). */
    public function generateAnnual(int $year): ReportAnnual
    {
        $annual = ReportAnnual::firstOrNew(['year' => $year]);
        $annual->status = $annual->status ?: 'OPEN';
        $annual->totals = $annual->totals ?: ['income' => 0, 'expense' => 0];
        $annual->generated_at = now();
        $annual->save();
        return $annual;
    }

    /** Build BOK Salur export (stub). */
    public function exportBokSalur(int $year, int $month): array
    {
        return [
            'period' => sprintf('%04d-%02d', $year, $month),
            'total_realization' => 0,
            'activities' => [],
        ];
    }
}

