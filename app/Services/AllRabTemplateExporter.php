<?php

namespace App\Services;

use App\Models\Rab;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AllRabTemplateExporter
{
    private string $filename;
    private ?string $templatePath = null;

    public function __construct(?string $filename = null)
    {
        $this->filename = $filename ?: ('ALL_RAB_' . now()->format('Y-m-d_H-i') . '.xlsx');
        // Prefer storage/app/templates/alltemplaterab.xlsx then fallback to resources/templates/alltemplaterab.xlsx
        $storagePath = storage_path('app/templates/alltemplaterab.xlsx');
        $resourcePath = resource_path('templates/alltemplaterab.xlsx');
        if (file_exists($storagePath)) {
            $this->templatePath = $storagePath;
        } elseif (file_exists($resourcePath)) {
            $this->templatePath = $resourcePath;
        }
    }

    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    public function download(): BinaryFileResponse
    {
        // Load template or build a default one if missing
        if ($this->templatePath && file_exists($this->templatePath)) {
            $spreadsheet = IOFactory::load($this->templatePath);
        } else {
            $spreadsheet = $this->buildDefaultTemplate();
            // Attempt to persist default template for future customization
            $defaultPath = storage_path('app/templates/alltemplaterab.xlsx');
            try {
                @mkdir(dirname($defaultPath), 0777, true);
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($defaultPath);
                $this->templatePath = $defaultPath;
            } catch (\Throwable $e) {
                // Ignore persistence failure; continue with in-memory template
            }
        }

        // Find the first sheet containing block markers; if not present, fallback uses whole sheet
        [$sheet, $startRow, $endRow] = $this->locateRabBlock($spreadsheet);

        // Collect template block merges within the block
        $blockMerges = $this->collectBlockMerges($sheet, $startRow, $endRow);

        // Store original template values before any replacements
        $originalTemplateValues = [];
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = 1; $col <= Coordinate::columnIndexFromString($sheet->getHighestColumn()); $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $originalTemplateValues[$row][$col] = $cell->getValue();
            }
        }

        // Determine last column on the sheet (used for style duplication)
        $lastColLetter = $sheet->getHighestColumn();
        $lastColIndex = Coordinate::columnIndexFromString($lastColLetter);
        $blockRowsCount = $endRow - $startRow + 1;

        // Pull all RABs ordered by komponen > rincian_menu > kegiatan
        $rabs = Rab::with('items')
            ->orderBy('komponen')
            ->orderBy('rincian_menu')
            ->orderBy('kegiatan')
            ->get();

        if ($rabs->isEmpty()) {
            // Just clear markers and return the template as is
            $this->clearPlaceholdersInRegion($sheet, $startRow, $endRow);
            return $this->stream($spreadsheet);
        }

        $currentInsertRow = $endRow + 1; // where the next block will be inserted

        // Fill the first (existing) block with the first RAB
        $firstRab = $rabs->shift();
        $rowDelta = $this->fillBlockForRab($sheet, $startRow, $endRow, $firstRab, $lastColLetter);
        $currentInsertRow = $endRow + 1 + $rowDelta; // shift if items expanded

        // Duplicate and fill blocks for the rest RABs
        foreach ($rabs as $index => $rab) {
            // Insert rows for a new block copy
            $sheet->insertNewRowBefore($currentInsertRow, $blockRowsCount);
            // Copy row heights, styles, and original template values, and replicate merges relative to this insertion
            for ($offset = 0; $offset < $blockRowsCount; $offset++) {
                $srcRow = $startRow + $offset;
                $dstRow = $currentInsertRow + $offset;
                // Duplicate style for the full row range
                $sheet->duplicateStyle($sheet->getStyle("A{$srcRow}:{$lastColLetter}{$srcRow}"), "A{$dstRow}:{$lastColLetter}{$dstRow}");
                // Copy row height
                $sheet->getRowDimension($dstRow)->setRowHeight(
                    $sheet->getRowDimension($srcRow)->getRowHeight()
                );
                // Copy original template values (placeholders etc.)
                for ($col = 1; $col <= $lastColIndex; $col++) {
                    $originalValue = $originalTemplateValues[$srcRow][$col] ?? '';
                    $dstCell = $sheet->getCellByColumnAndRow($col, $dstRow);
                    $dstCell->setValue($originalValue);
                }
            }
            // Replicate merges inside the block
            foreach ($blockMerges as [$c1, $r1, $c2, $r2]) {
                $nr1 = $currentInsertRow + ($r1 - $startRow);
                $nr2 = $currentInsertRow + ($r2 - $startRow);
                $range = Coordinate::stringFromColumnIndex($c1) . $nr1 . ':' . Coordinate::stringFromColumnIndex($c2) . $nr2;
                $sheet->mergeCells($range);
            }

            // Fill newly inserted block for this RAB
            $newBlockStart = $currentInsertRow;
            $newBlockEnd = $currentInsertRow + $blockRowsCount - 1;
            $rowDelta = $this->fillBlockForRab($sheet, $newBlockStart, $newBlockEnd, $rab, $lastColLetter);
            $currentInsertRow = $newBlockEnd + 1 + $rowDelta;
        }

        return $this->stream($spreadsheet);
    }

    private function buildDefaultTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ALL RAB');

        // Minimal block with header placeholders and one [[ITEMS]] row
        // Place [[RAB_BLOCK_START]] at row 1, col A
        $sheet->setCellValue('A1', '[[RAB_BLOCK_START]]');
        $sheet->setCellValue('B1', 'RAB - [[KEGIATAN]]');

        $sheet->setCellValue('A3', 'Komponen');
        $sheet->setCellValue('B3', '[[KOMPONEN]]');
        $sheet->setCellValue('A4', 'Rincian Menu');
        $sheet->setCellValue('B4', '[[RINCIAN_MENU]]');
        $sheet->setCellValue('A5', 'Kegiatan');
        $sheet->setCellValue('B5', '[[KEGIATAN]]');
        $sheet->setCellValue('A6', 'Total');
        $sheet->setCellValue('B6', '[[TOTAL]]');
        $sheet->setCellValue('A7', 'Jumlah Item');
        $sheet->setCellValue('B7', '[[ITEM_COUNT]]');

        // Table header
        $sheet->setCellValue('A9', 'No');
        $sheet->setCellValue('B9', 'Item');
        $sheet->setCellValue('C9', 'Faktor Perkalian');
        $sheet->setCellValue('D9', 'Harga Satuan (Rp)');
        $sheet->setCellValue('E9', 'Sub Total (Rp)');

        // Items template row
        $sheet->setCellValue('A10', '[[ITEMS]]');

        // Block end marker after items row
        $sheet->setCellValue('A12', '[[RAB_BLOCK_END]]');

        // Basic bold for headers
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A3:A7')->getFont()->setBold(true);
        $sheet->getStyle('A9:E9')->getFont()->setBold(true);

        // Column widths
        foreach (['A'=>6,'B'=>35,'C'=>40,'D'=>18,'E'=>18] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        return $spreadsheet;
    }

    private function locateRabBlock(Spreadsheet $spreadsheet): array
    {
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            $startRow = null;
            $endRow = null;
            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    $txt = is_string($val) ? trim($val) : '';
                    if ($txt === '[[RAB_BLOCK_START]]') {
                        $startRow = $row;
                    } elseif ($txt === '[[RAB_BLOCK_END]]') {
                        $endRow = $row;
                    }
                    if ($startRow && $endRow && $endRow >= $startRow) {
                        return [$sheet, $startRow, $endRow];
                    }
                }
            }
            // Fallback: if no explicit markers, use whole used range as a block
            if ($highestRow > 0 && $highestColumnIndex > 0) {
                return [$sheet, 1, $highestRow];
            }
        }
        return [null, null, null];
    }

    private function collectBlockMerges($sheet, int $startRow, int $endRow): array
    {
        $merges = [];
        foreach ($sheet->getMergeCells() as $range) {
            if (preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $range, $m)) {
                $c1 = Coordinate::columnIndexFromString($m[1]);
                $r1 = (int) $m[2];
                $c2 = Coordinate::columnIndexFromString($m[3]);
                $r2 = (int) $m[4];
                if ($r1 >= $startRow && $r2 <= $endRow) {
                    $merges[] = [$c1, $r1, $c2, $r2];
                }
            }
        }
        return $merges;
    }

    private function fillBlockForRab($sheet, int $blockStart, int $blockEnd, Rab $rab, string $lastColLetter): int
    {
        $lastColIndex = Coordinate::columnIndexFromString($lastColLetter);
        $items = $rab->items()->get()->values();

        // Build replacements
        $stringReplacements = [
            '[[KOMPONEN]]' => (string) $rab->komponen,
            '[[RINCIAN_MENU]]' => (string) $rab->rincian_menu,
            '[[KEGIATAN]]' => (string) $rab->kegiatan,
            '[[ITEM_COUNT]]' => (string) $items->count(),

            // Custom fields untuk template Puskesmas
            '[[TAHUN]]' => date('Y'),
            '[[NAMA_PUSKESMAS]]' => 'PUSKESMAS PEMBANGUNAN', // Ganti dengan config
            '[[ALAMAT_PUSKESMAS]]' => 'Jl. Merdeka No. 123', // Ganti dengan config
            '[[KOTA_PUSKESMAS]]' => 'KOTA BANDUNG', // Ganti dengan config
            '[[NO_DOKUMEN]]' => 'RAB/' . date('Y') . '/001',
            '[[TANGGAL]]' => date('d F Y'),
            '[[SUMBER_DANA]]' => 'Dana Alokasi Umum (DAU)',
            '[[NAMA_KEPALA]]' => 'dr. SISWOYO, M.Kes', // Ganti dengan config
            '[[NIP_KEPALA]]' => '19760523 200312 1 001', // Ganti dengan config
        ];
        $numericReplacements = [
            '[[TOTAL]]' => (float) $rab->total,
        ];

        // Enumerated placeholders for each item
        foreach ($items as $i => $item) {
            $idx = $i + 1;
            $prefix = '[[ITEM' . $idx . '_';
            $stringReplacements[$prefix . 'LABEL]]'] = (string) $item->label;
            $numericReplacements[$prefix . 'UNIT_PRICE]]'] = (float) $item->unit_price;
            $numericReplacements[$prefix . 'SUBTOTAL]]'] = (float) $item->subtotal;
            $factorPhrase = collect($item->factors ?? [])->map(function ($f) {
                $label = $f['label'] ?? ($f['key'] ?? '-');
                $value = (float)($f['value'] ?? 0);
                return $label . ' x ' . $value;
            })->join(' × ');
            $stringReplacements[$prefix . 'FACTOR_PHRASE]]'] = $factorPhrase;

            $factors = collect($item->factors ?? [])->values();
            foreach ($factors as $j => $f) {
                $jdx = $j + 1;
                $label = $f['label'] ?? ($f['key'] ?? '');
                $value = (float)($f['value'] ?? 0);
                $stringReplacements[$prefix . 'FACTOR' . $jdx . '_LABEL]]'] = (string) $label;
                $numericReplacements[$prefix . 'FACTOR' . $jdx . '_VALUE]]'] = (float) $value;
            }
        }

        // Replace placeholders inside region
        for ($row = $blockStart; $row <= $blockEnd; $row++) {
            for ($col = 1; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $text = is_string($val) ? $val : (is_null($val) ? '' : (string) $val);
                if ($text === '') continue;
                $trim = trim($text);
                if (array_key_exists($trim, $numericReplacements)) {
                    $cell->setValueExplicit($numericReplacements[$trim], DataType::TYPE_NUMERIC);
                } else {
                    // Allow inline replacements including numeric turned into string
                    $inlineMap = $stringReplacements + array_map(fn($n) => (string)(is_float($n) ? (0 + $n) : $n), $numericReplacements);
                    foreach ($inlineMap as $k => $v) {
                        if ($v === null) continue;
                        if ($text !== '' && str_contains($text, $k)) {
                            $cell->setValue(str_replace($k, $v, $text));
                            $text = (string) $cell->getValue();
                        }
                    }
                }
            }
        }

        // Expand [[ITEMS]] inside this region (if exists)
        $insertedRows = $this->expandItemsInRegion($sheet, $blockStart, $blockEnd, $items, $lastColLetter);

        // Cleanup any leftover placeholders in region
        $this->clearPlaceholdersInRegion($sheet, $blockStart, $blockEnd + $insertedRows);

        // Return how many rows were inserted (affects next block insert position)
        return $insertedRows;
    }

    private function expandItemsInRegion($sheet, int $startRow, int $endRow, $items, string $lastColLetter): int
    {
        $lastColIndex = Coordinate::columnIndexFromString($lastColLetter);
        $itemsRow = null;
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = 1; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $txt = is_string($val) ? trim($val) : '';
                if ($txt === '[[ITEMS]]') {
                    $itemsRow = $row;
                    break 2;
                }
            }
        }
        if (!$itemsRow) return 0;

        $count = $items->count();
        $inserted = 0;
        $templateRange = "A{$itemsRow}:{$lastColLetter}{$itemsRow}";

        // Collect merges belonging only to the items row
        $rowMerges = [];
        foreach ($sheet->getMergeCells() as $range) {
            if (preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $range, $m)) {
                $r1 = (int) $m[2];
                $r2 = (int) $m[4];
                if ($r1 === $itemsRow && $r2 === $itemsRow) {
                    $rowMerges[] = [$m[1], $m[3]]; // [startCol, endCol]
                }
            }
        }

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $targetRow = $itemsRow + $i;
                if ($i > 0) {
                    $sheet->insertNewRowBefore($targetRow, 1);
                    $sheet->duplicateStyle($sheet->getStyle($templateRange), "A{$targetRow}:{$lastColLetter}{$targetRow}");
                    $sheet->getRowDimension($targetRow)->setRowHeight(
                        $sheet->getRowDimension($itemsRow)->getRowHeight()
                    );
                    foreach ($rowMerges as [$sc, $ec]) {
                        $sheet->mergeCells("{$sc}{$targetRow}:{$ec}{$targetRow}");
                    }
                    $inserted++;
                }
                $item = $items[$i];
                $faktor = collect($item->factors ?? [])->map(function ($f) {
                    $label = $f['label'] ?? ($f['key'] ?? '-');
                    $value = (float)($f['value'] ?? 0);
                    return $label . ' x ' . $value;
                })->join(' × ');
                $sheet->setCellValue("A{$targetRow}", $i + 1);
                $sheet->setCellValue("B{$targetRow}", $item->label);
                $sheet->setCellValue("C{$targetRow}", $faktor);
                $sheet->setCellValueExplicit("D{$targetRow}", (float) $item->unit_price, DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit("E{$targetRow}", (float) $item->subtotal, DataType::TYPE_NUMERIC);
            }
            // Clean any remaining [[ITEMS]] tokens on original row
            for ($col = 1; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $itemsRow);
                $v = $cell->getValue();
                $t = is_string($v) ? trim($v) : '';
                if ($t === '[[ITEMS]]') {
                    $cell->setValue('');
                }
            }
        } else {
            // Zero items: clear the template row
            for ($col = 1; $col <= $lastColIndex; $col++) {
                $sheet->getCellByColumnAndRow($col, $itemsRow)->setValue('');
            }
        }

        return $inserted;
    }

    private function clearPlaceholdersInRegion($sheet, int $startRow, int $endRow): void
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                if (is_string($val) && preg_match('/\[\[.*\]\]/', $val)) {
                    $cell->setValue('');
                }
            }
        }
    }

    private function stream(Spreadsheet $spreadsheet): BinaryFileResponse
    {
        // Write to a temporary file path and return as a binary download
        $tempPath = tempnam(sys_get_temp_dir(), 'rab_all_');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempPath);

        return response()->download($tempPath, $this->filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
