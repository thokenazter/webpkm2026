<?php

namespace App\Services;

use App\Models\Rab;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class RabMasterTemplateExporter
{
    private array $components;
    private string $filename;
    private string $templatePath;

    public function __construct(string $filename = 'MASTER_RAB_TEMPLATE.xlsx')
    {
        $this->components = Rab::components();
        $this->filename = $filename;
        $this->templatePath = resource_path('templates/master_rab_template.xlsx');
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function download(): StreamedResponse
    {
        // Check if custom template exists
        if (file_exists($this->templatePath)) {
            return $this->downloadFromCustomTemplate();
        } else {
            // Fallback to generated template
            return $this->downloadFromGeneratedTemplate();
        }
    }

    private function downloadFromCustomTemplate(): StreamedResponse
    {
        $spreadsheet = IOFactory::load($this->templatePath);

        // Process each sheet in the template
        $this->processTemplateSheets($spreadsheet);

        return $this->streamResponse($spreadsheet);
    }

    private function downloadFromGeneratedTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();

        // Create all sheets dynamically
        $this->createSummarySheet($spreadsheet);

        $sheetIndex = 1;
        foreach ($this->components as $key => $name) {
            $rabsInComponent = Rab::where('komponen', $name)
                ->with(['items', 'menu', 'kegiatanRef'])
                ->orderBy('rincian_menu')
                ->orderBy('kegiatan')
                ->get();

            if ($rabsInComponent->count() > 0) {
                $this->createComponentSheet($spreadsheet, $name, $rabsInComponent, $sheetIndex);
                $sheetIndex++;
            }
        }

        return $this->streamResponse($spreadsheet);
    }

    private function processTemplateSheets(Spreadsheet $spreadsheet): void
    {
        // Process Summary Sheet
        $summarySheet = $spreadsheet->getSheetByName('Summary') ?? $spreadsheet->getSheet(0);
        $this->processSummarySheet($summarySheet);

        // Process Component Sheets
        foreach ($this->components as $key => $name) {
            $sheet = $spreadsheet->getSheetByName($name) ?? $spreadsheet->getSheetByName("Sheet " . ($key + 1));
            if ($sheet) {
                $rabsInComponent = Rab::where('komponen', $name)
                    ->with(['items', 'menu', 'kegiatanRef'])
                    ->orderBy('rincian_menu')
                    ->orderBy('kegiatan')
                    ->get();

                if ($rabsInComponent->count() > 0) {
                    $this->processComponentSheet($sheet, $name, $rabsInComponent);
                }
            }
        }
    }

    private function processSummarySheet($sheet): void
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

        // Replace placeholders in summary sheet
        $stringReplacements = [
            '[[EXPORT_DATE]]' => now()->format('d F Y H:i'),
            '[[TOTAL_BUDGET]]' => number_format($totalBudget, 0, ',', '.'),
            '[[TOTAL_RABS]]' => $totalRabs,
            '[[AVG_TOTAL]]' => number_format($totalRabs > 0 ? $totalBudget / $totalRabs : 0, 0, ',', '.'),
        ];

        $numericReplacements = [
            '[[TOTAL_BUDGET_NUMERIC]]' => (float) $totalBudget,
            '[[TOTAL_RABS_NUMERIC]]' => (float) $totalRabs,
        ];

        $this->replacePlaceholders($sheet, $stringReplacements, $numericReplacements);

        // Fill component data
        $startRow = $this->findPlaceholderRow($sheet, '[[COMPONENT_DATA]]');
        if ($startRow) {
            $this->fillComponentData($sheet, $summaryData, $startRow);
        }
    }

    private function processComponentSheet($sheet, string $componentName, $rabs): void
    {
        // Group data
        $groupedData = $rabs->groupBy('rincian_menu')->map(function ($menuGroup) {
            return $menuGroup->groupBy('kegiatan');
        });

        $totalBudget = $rabs->sum('total');
        $totalRabs = $rabs->count();

        // Replace component-specific placeholders
        $stringReplacements = [
            '[[COMPONENT_NAME]]' => $componentName,
            '[[TOTAL_BUDGET]]' => number_format($totalBudget, 0, ',', '.'),
            '[[TOTAL_RABS]]' => $totalRabs,
        ];

        $numericReplacements = [
            '[[TOTAL_BUDGET_NUMERIC]]' => (float) $totalBudget,
            '[[TOTAL_RABS_NUMERIC]]' => (float) $totalRabs,
        ];

        $this->replacePlaceholders($sheet, $stringReplacements, $numericReplacements);

        // Fill detailed data
        $startRow = $this->findPlaceholderRow($sheet, '[[ITEM_DATA]]');
        if ($startRow) {
            $this->fillComponentDataItems($sheet, $groupedData, $startRow);
        }
    }

    private function createSummarySheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Summary Dashboard');

        // Create summary structure
        $sheet->setCellValue('A1', 'MASTER DASHBOARD RAB BOK PUSKESMAS');
        $sheet->setCellValue('A2', 'Export Date: ' . now()->format('d F Y H:i'));
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Komponen');
        $sheet->setCellValue('C4', 'Jumlah RAB');
        $sheet->setCellValue('D4', 'Total Anggaran (Rp)');
        $sheet->setCellValue('E4', 'Rata-rata (Rp)');

        // Style headers
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);
        $sheet->getStyle('A4:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E3F2FD');

        // Fill data
        $row = 5;
        $totalBudget = 0;
        $totalRabs = 0;

        foreach ($this->components as $key => $name) {
            $rabsInComponent = Rab::where('komponen', $name);
            $count = $rabsInComponent->count();
            $total = $rabsInComponent->sum('total');

            $sheet->setCellValue('A' . $row, $row - 4);
            $sheet->setCellValue('B' . $row, $name);
            $sheet->setCellValue('C' . $row, $count);
            $sheet->setCellValueExplicit('D' . $row, (float) $total, DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('E' . $row, (float) ($count > 0 ? $total / $count : 0), DataType::TYPE_NUMERIC);

            $totalBudget += $total;
            $totalRabs += $count;
            $row++;
        }

        // Total row
        $sheet->setCellValue('B' . $row, 'TOTAL');
        $sheet->setCellValue('C' . $row, $totalRabs);
        $sheet->setCellValueExplicit('D' . $row, (float) $totalBudget, DataType::TYPE_NUMERIC);
        $sheet->setCellValueExplicit('E' . $row, (float) ($totalRabs > 0 ? $totalBudget / $totalRabs : 0), DataType::TYPE_NUMERIC);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFF3E0');

        // Auto-size columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function createComponentSheet(Spreadsheet $spreadsheet, string $componentName, $rabs, int $sheetIndex): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(substr($componentName, 0, 31)); // Excel sheet name max 31 chars

        // Group data
        $groupedData = $rabs->groupBy('rincian_menu')->map(function ($menuGroup) {
            return $menuGroup->groupBy('kegiatan');
        });

        // Headers
        $sheet->setCellValue('A1', $componentName);
        $sheet->setCellValue('A2', 'Total RAB: ' . $rabs->count() . ' | Total Budget: Rp ' . number_format($rabs->sum('total'), 0, ',', '.'));
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Rincian Menu');
        $sheet->setCellValue('C4', 'Kegiatan');
        $sheet->setCellValue('D4', 'Item');
        $sheet->setCellValue('E4', 'Faktor Perkalian');
        $sheet->setCellValue('F4', 'Harga Satuan (Rp)');
        $sheet->setCellValue('G4', 'Sub Total (Rp)');

        // Style headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E3F2FD');

        // Fill data
        $row = 5;
        $itemCounter = 1;

        foreach ($groupedData as $menuName => $menuGroup) {
            foreach ($menuGroup as $kegiatanName => $kegiatanRabs) {
                foreach ($kegiatanRabs as $rab) {
                    foreach ($rab->items as $item) {
                        $sheet->setCellValue('A' . $row, $itemCounter);
                        $sheet->setCellValue('B' . $row, $menuName);
                        $sheet->setCellValue('C' . $row, $kegiatanName);
                        $sheet->setCellValue('D' . $row, $item->label);

                        $factors = collect($item->factors ?? [])->map(function($f) {
                            $label = $f['label'] ?? $f['key'] ?? '-';
                            $value = (float)($f['value'] ?? 0);
                            return $label . ' x ' . $value;
                        })->join(' × ');

                        $sheet->setCellValue('E' . $row, $factors ?: '-');
                        $sheet->setCellValueExplicit('F' . $row, (float) $item->unit_price, DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicit('G' . $row, (float) $item->subtotal, DataType::TYPE_NUMERIC);

                        $row++;
                        $itemCounter++;
                    }
                }
            }
        }

        // Total row
        $sheet->setCellValue('F' . $row, 'TOTAL ' . $componentName);
        $sheet->setCellValueExplicit('G' . $row, (float) $rabs->sum('total'), DataType::TYPE_NUMERIC);
        $sheet->getStyle('F' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('F' . $row . ':G' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFF3E0');

        // Auto-size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function replacePlaceholders($sheet, array $stringReplacements, array $numericReplacements): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $text = is_string($val) ? $val : (is_null($val) ? '' : (string) $val);

                if ($text !== '') {
                    $trim = trim($text);
                    if (array_key_exists($trim, $numericReplacements)) {
                        $cell->setValueExplicit($numericReplacements[$trim], DataType::TYPE_NUMERIC);
                    } else {
                        // String replacements
                        foreach ($stringReplacements as $placeholder => $value) {
                            if (str_contains($text, $placeholder)) {
                                $cell->setValue(str_replace($placeholder, $value, $text));
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    private function findPlaceholderRow($sheet, string $placeholder): ?int
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                if (is_string($val) && trim($val) === $placeholder) {
                    return $row;
                }
            }
        }
        return null;
    }

    private function fillComponentData($sheet, array $data, int $startRow): void
    {
        $row = $startRow;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item['komponen']);
            $sheet->setCellValue('C' . $row, $item['count']);
            $sheet->setCellValueExplicit('D' . $row, (float) $item['total'], DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('E' . $row, (float) $item['avg_total'], DataType::TYPE_NUMERIC);
            $row++;
        }
    }

    private function fillComponentDataItems($sheet, $groupedData, int $startRow): void
    {
        $row = $startRow;
        $itemCounter = 1;

        foreach ($groupedData as $menuName => $menuGroup) {
            foreach ($menuGroup as $kegiatanName => $kegiatanRabs) {
                foreach ($kegiatanRabs as $rab) {
                    foreach ($rab->items as $item) {
                        $sheet->setCellValue('A' . $row, $itemCounter);
                        $sheet->setCellValue('B' . $row, $menuName);
                        $sheet->setCellValue('C' . $row, $kegiatanName);
                        $sheet->setCellValue('D' . $row, $item->label);

                        $factors = collect($item->factors ?? [])->map(function($f) {
                            $label = $f['label'] ?? $f['key'] ?? '-';
                            $value = (float)($f['value'] ?? 0);
                            return $label . ' x ' . $value;
                        })->join(' × ');

                        $sheet->setCellValue('E' . $row, $factors ?: '-');
                        $sheet->setCellValueExplicit('F' . $row, (float) $item->unit_price, DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicit('G' . $row, (float) $item->subtotal, DataType::TYPE_NUMERIC);

                        $row++;
                        $itemCounter++;
                    }
                }
            }
        }
    }

    private function streamResponse(Spreadsheet $spreadsheet): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, $this->filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}