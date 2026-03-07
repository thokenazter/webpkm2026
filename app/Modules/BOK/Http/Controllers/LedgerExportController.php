<?php

namespace App\Modules\BOK\Http\Controllers;

use App\Modules\BOK\Exports\LedgerExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LedgerExportController
{
    public function export(Request $request)
    {
        $request->validate([
            'year' => ['required','integer','min:2000','max:2100'],
            'month' => ['nullable','integer','min:1','max:12'],
        ]);
        $year = (int) $request->integer('year');
        $month = $request->filled('month') ? (int) $request->integer('month') : null;

        $filename = 'BKU_' . $year . ($month ? ('_' . str_pad($month, 2, '0', STR_PAD_LEFT)) : '') . '.xlsx';
        return Excel::download(new LedgerExport($year, $month), $filename);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'year' => ['required','integer','min:2000','max:2100'],
            'month' => ['nullable','integer','min:1','max:12'],
        ]);
        $year = (int) $request->integer('year');
        $month = $request->filled('month') ? (int) $request->integer('month') : null;

        $html = view('print.ledger', compact('year', 'month'))->render();

        // If DomPDF is available, render as PDF; otherwise fallback to HTML response
        if (app()->bound('dompdf.wrapper')) {
            $pdf = app('dompdf.wrapper');
            $pdf->loadHTML($html)->setPaper('a4', 'portrait');
            $filename = 'BKU_' . $year . ($month ? ('_' . str_pad($month, 2, '0', STR_PAD_LEFT)) : '') . '.pdf';
            return $pdf->download($filename);
        }

        return response($html)->header('X-PDF-Fallback', '1');
    }
}
