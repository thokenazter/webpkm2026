<?php

namespace App\Exports;

use App\Models\Rab;
use App\Models\RabMenu;
use App\Models\RabKegiatan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RabMasterExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // 1. Summary Dashboard Sheet
        $sheets[] = new RabSummarySheet();

        // 2. Sheets per komponen
        $components = Rab::components();
        foreach ($components as $key => $name) {
            $rabsInComponent = Rab::where('komponen', $name)
                ->with(['items', 'menu', 'kegiatanRef'])
                ->orderBy('rincian_menu')
                ->orderBy('kegiatan')
                ->get();

            if ($rabsInComponent->count() > 0) {
                $sheets[] = new RabComponentSheet($name, $rabsInComponent);
            }
        }

        // 3. All RABs List Sheet (optional reference)
        $sheets[] = new RabAllListSheet();

        return $sheets;
    }
}

// Summary Dashboard Sheet
class RabSummarySheet implements FromView, WithColumnWidths, WithStyles
{
    public function view(): View
    {
        $components = Rab::components();
        $summaryData = [];

        $totalBudget = 0;
        $totalRabs = 0;

        foreach ($components as $key => $name) {
            $rabsInComponent = Rab::where('komponen', $name);
            $count = $rabsInComponent->count();
            $total = $rabsInComponent->sum('total');

            $summaryData[] = [
                'komponen' => $name,
                'count' => $count,
                'total' => $total,
                'avg_total' => $count > 0 ? $total / $count : 0
            ];

            $totalBudget += $total;
            $totalRabs += $count;
        }

        return view('exports.rab-summary', [
            'summaryData' => $summaryData,
            'totalBudget' => $totalBudget,
            'totalRabs' => $totalRabs,
            'exportDate' => now()->format('d F Y H:i')
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 50,  // Komponen
            'C' => 12,  // Jumlah RAB
            'D' => 20,  // Total Anggaran
            'E' => 20,  // Rata-rata
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
            'A2:E2' => ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E3F2FD']]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}

// Component Sheet
class RabComponentSheet implements FromView, WithColumnWidths, WithStyles
{
    private $componentName;
    private $rabs;

    public function __construct(string $componentName, Collection $rabs)
    {
        $this->componentName = $componentName;
        $this->rabs = $rabs;
    }

    public function view(): View
    {
        // Group by rincian_menu then kegiatan
        $groupedData = $this->rabs->groupBy('rincian_menu')->map(function ($menuGroup) {
            return $menuGroup->groupBy('kegiatan');
        });

        return view('exports.rab-component', [
            'componentName' => $this->componentName,
            'groupedData' => $groupedData,
            'totalBudget' => $this->rabs->sum('total'),
            'totalRabs' => $this->rabs->count()
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 30,  // Rincian Menu
            'C' => 35,  // Kegiatan
            'D' => 35,  // Item
            'E' => 25,  // Faktor
            'F' => 15,  // Harga Satuan
            'G' => 15,  // Sub Total
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            2 => ['font' => ['bold' => true]],
            'A2:G2' => ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E3F2FD']]],
        ];
    }
}

// All RABs List Sheet
class RabAllListSheet implements FromView, WithColumnWidths, WithStyles
{
    public function view(): View
    {
        $allRabs = Rab::with(['items', 'menu', 'kegiatanRef'])
            ->orderBy('komponen')
            ->orderBy('rincian_menu')
            ->orderBy('kegiatan')
            ->get();

        return view('exports.rab-all-list', [
            'rabs' => $allRabs
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 40,  // Komponen
            'C' => 25,  // Rincian Menu
            'D' => 30,  // Kegiatan
            'E' => 15,  // Total Items
            'F' => 20,  // Total Budget
            'G' => 15,  // Created Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A1:G1' => ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E3F2FD']]],
        ];
    }
}