<?php

namespace App\Exports;

use App\Models\Rab;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class RabExport implements FromView, WithColumnWidths
{
    public function __construct(private Rab $rab)
    {
    }

    public function view(): View
    {
        return view('export.rab', [
            'rab' => $this->rab->load('items'),
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 32,  // Item
            'C' => 50,  // Rincian Faktor
            'D' => 16,  // Harga Satuan
            'E' => 20,  // Sub Total
        ];
    }
}

