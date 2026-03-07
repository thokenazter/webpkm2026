<?php

namespace App\Modules\BOK\Http\Controllers;

use App\Modules\BOK\Models\ReportMonthly;
use Illuminate\Http\Request;

class LedgerPeriodController
{
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'year' => ['required','integer','min:2000','max:2100'],
            'month' => ['required','integer','min:1','max:12'],
        ]);
        $report = ReportMonthly::firstOrCreate([
            'year' => (int) $data['year'],
            'month' => (int) $data['month'],
        ]);
        $report->status = $report->status === 'LOCKED' ? 'OPEN' : 'LOCKED';
        $report->save();
        return back()->with('success', 'Periode ' . $report->year . '-' . str_pad($report->month, 2, '0', STR_PAD_LEFT) . ' diubah ke status ' . $report->status);
    }
}

