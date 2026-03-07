<?php

namespace App\Services;

use App\Models\Rab;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Response;

class MultiRabStackedTemplateExporter
{
    private string $filename;
    /** @var Rab[] */
    private array $rabs;

    public function __construct(array $rabs, ?string $filename = null)
    {
        $this->rabs = $rabs;
        $this->filename = $filename ?: ('RAB_MULTI_' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    public function download(): Response
    {
        $template = $this->resolveTemplatePath();
        if (!file_exists($template)) {
            throw new \RuntimeException('Template RAB tidak ditemukan di storage/app/templates atau resources/templates');
        }

        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getActiveSheet();

        // Determine block region (fallback to whole used range)
        [$startRow, $startCol, $endRow, $endCol] = $this->determineBlockArea($sheet);
        $startColLetter = Coordinate::stringFromColumnIndex($startCol);
        $endColLetter = Coordinate::stringFromColumnIndex($endCol);
        $lastColIndex = $endCol;
        $blockRows = $endRow - $startRow + 1;

        // Capture original template values in the block before any replacement
        $originalTemplateValues = [];
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = 1; $col <= $lastColIndex; $col++) {
                $originalTemplateValues[$row][$col] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }

        if (count($this->rabs) === 0) {
            return $this->stream($spreadsheet);
        }

        // Fill the first (existing) block with the first Rab
        $first = array_shift($this->rabs);
        $delta = $this->fillBlockForRab($sheet, $startRow, $endRow, $first, $startCol, $endCol);
        $insertAt = $endRow + 1 + $delta;

        // Duplicate for the rest
        foreach ($this->rabs as $rab) {
            $sheet->insertNewRowBefore($insertAt, $blockRows);
            // Copy styles, heights, and values for each row in block
            for ($off = 0; $off < $blockRows; $off++) {
                $src = $startRow + $off;
                $dst = $insertAt + $off;
                // Duplicate style per-cell to preserve wrap, fill, borders precisely
                for ($col = $startCol; $col <= $lastColIndex; $col++) {
                    $addr = Coordinate::stringFromColumnIndex($col) . $dst;
                    $sheet->duplicateStyle($sheet->getStyleByColumnAndRow($col, $src), $addr);
                }
                $sheet->getRowDimension($dst)->setRowHeight($sheet->getRowDimension($src)->getRowHeight());
                for ($col = $startCol; $col <= $lastColIndex; $col++) {
                    $orig = $originalTemplateValues[$src][$col] ?? '';
                    $sheet->getCellByColumnAndRow($col, $dst)->setValue($orig);
                }
            }
            // replicate merges inside the block
            foreach ($sheet->getMergeCells() as $range) {
                if (preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $range, $m)) {
                    $c1 = Coordinate::columnIndexFromString($m[1]);
                    $r1 = (int) $m[2];
                    $c2 = Coordinate::columnIndexFromString($m[3]);
                    $r2 = (int) $m[4];
                    if ($r1 >= $startRow && $r2 <= $endRow && $c1 >= $startCol && $c2 <= $endCol) {
                        $nr1 = $insertAt + ($r1 - $startRow);
                        $nr2 = $insertAt + ($r2 - $startRow);
                        $sheet->mergeCells(Coordinate::stringFromColumnIndex($c1) . $nr1 . ':' . Coordinate::stringFromColumnIndex($c2) . $nr2);
                    }
                }
            }

            $delta = $this->fillBlockForRab($sheet, $insertAt, $insertAt + $blockRows - 1, $rab, $startCol, $endCol);
            $insertAt = $insertAt + $blockRows + $delta;
        }

        return $this->stream($spreadsheet);
    }

    private function resolveTemplatePath(): string
    {
        $storageTemplate = storage_path('app/templates/templaterab.xlsx');
        $resourceTemplate = resource_path('templates/templaterab.xlsx');
        return file_exists($storageTemplate) ? $storageTemplate : $resourceTemplate;
    }

    private function determineBlockArea($sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        // Try markers [[STACK_BLOCK_START]] and [[STACK_BLOCK_END]]
        $start = null; $end = null;
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColIndex; $col++) {
                $val = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                $txt = is_string($val) ? trim($val) : '';
                if ($txt === '[[STACK_BLOCK_START]]') {
                    $start = [$row, $col];
                }
                if ($txt === '[[STACK_BLOCK_END]]') {
                    $end = [$row, $col];
                }
                if ($start && $end) break 2;
            }
        }
        if ($start && $end) {
            // Clean markers
            $sheet->getCellByColumnAndRow($start[1], $start[0])->setValue('');
            $sheet->getCellByColumnAndRow($end[1], $end[0])->setValue('');
            // Normalize so start is top-left and end is bottom-right
            $sr = min($start[0], $end[0]);
            $er = max($start[0], $end[0]);
            $sc = min($start[1], $end[1]);
            $ec = max($start[1], $end[1]);
            return [$sr, $sc, $er, $ec];
        }

        // Fallback to whole used sheet
        if ($highestRow < 1 || $highestColIndex < 1) return [1, 1, 1, 1];
        return [1, 1, $highestRow, $highestColIndex];
    }

    private function fillBlockForRab($sheet, int $blockStart, int $blockEnd, Rab $rab, int $startCol, int $endCol): int
    {
        $lastColIndex = $endCol;
        $items = $rab->items()->get()->values();

        $stringReplacements = [
            '[[KOMPONEN]]' => (string) $rab->komponen,
            '[[RINCIAN_MENU]]' => (string) $rab->rincian_menu,
            '[[KEGIATAN]]' => (string) $rab->kegiatan,
            '[[ITEM_COUNT]]' => (string) $items->count(),
        ];
        $numericReplacements = [
            '[[TOTAL]]' => (float) $rab->total,
        ];

        foreach ($items as $i => $item) {
            $idx = $i + 1;
            $prefix = '[[ITEM' . $idx . '_';
            $stringReplacements[$prefix . 'LABEL]]'] = (string) $item->label;
            $numericReplacements[$prefix . 'UNIT_PRICE]]'] = (float) $item->unit_price;
            $numericReplacements[$prefix . 'SUBTOTAL]]'] = (float) $item->subtotal;
            $phrase = collect($item->factors ?? [])->map(function ($f) {
                $label = $f['label'] ?? ($f['key'] ?? '-');
                $value = (float)($f['value'] ?? 0);
                return $label . ' x ' . $value;
            })->join(' × ');
            $stringReplacements[$prefix . 'FACTOR_PHRASE]]'] = $phrase;

            $factors = collect($item->factors ?? [])->values();
            foreach ($factors as $j => $f) {
                $jdx = $j + 1;
                $label = $f['label'] ?? ($f['key'] ?? '');
                $value = (float)($f['value'] ?? 0);
                $stringReplacements[$prefix . 'FACTOR' . $jdx . '_LABEL]]'] = (string) $label;
                $numericReplacements[$prefix . 'FACTOR' . $jdx . '_VALUE]]'] = (float) $value;
            }
        }

        for ($row = $blockStart; $row <= $blockEnd; $row++) {
            for ($col = $startCol; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $text = is_string($val) ? $val : (is_null($val) ? '' : (string) $val);
                if ($text === '') continue;
                $trim = trim($text);
                if (array_key_exists($trim, $numericReplacements)) {
                    $cell->setValueExplicit($numericReplacements[$trim], DataType::TYPE_NUMERIC);
                } else {
                    $inline = $stringReplacements + array_map(fn($n) => (string)(is_float($n) ? (0 + $n) : $n), $numericReplacements);
                    foreach ($inline as $k => $v) {
                        if ($v === null) continue;
                        if ($text !== '' && str_contains($text, $k)) {
                            $cell->setValue(str_replace($k, $v, $text));
                            $text = (string) $cell->getValue();
                        }
                    }
                }
            }
        }

        $inserted = $this->expandItemsInRegion($sheet, $blockStart, $blockEnd, $items, $startCol, $endCol);
        $this->clearRemainingPlaceholders($sheet, $blockStart, $blockEnd + $inserted, $startCol, $endCol);
        return $inserted;
    }

    private function expandItemsInRegion($sheet, int $startRow, int $endRow, $items, int $startCol, int $endCol): int
    {
        $lastColIndex = $endCol;
        $itemsRow = null;
        $itemsCol = null;
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = $startCol; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $txt = is_string($val) ? trim($val) : '';
                if ($txt === '[[ITEMS]]') { $itemsRow = $row; $itemsCol = $col; break 2; }
            }
        }
        if (!$itemsRow) return 0;

        $count = $items->count();
        $inserted = 0;
        $startColLetter = Coordinate::stringFromColumnIndex($startCol);
        $endColLetter = Coordinate::stringFromColumnIndex($endCol);
        $templateRange = $startColLetter . $itemsRow . ':' . $endColLetter . $itemsRow;

        $rowMerges = [];
        foreach ($sheet->getMergeCells() as $range) {
            if (preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $range, $m)) {
                $r1 = (int) $m[2];
                $r2 = (int) $m[4];
                if ($r1 === $itemsRow && $r2 === $itemsRow) {
                    $rowMerges[] = [$m[1], $m[3]];
                }
            }
        }

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $target = $itemsRow + $i;
                if ($i > 0) {
                    $sheet->insertNewRowBefore($target, 1);
                    // Duplicate style per-cell for items row
                    for ($col = $startCol; $col <= $lastColIndex; $col++) {
                        $addr = Coordinate::stringFromColumnIndex($col) . $target;
                        $sheet->duplicateStyle($sheet->getStyleByColumnAndRow($col, $itemsRow), $addr);
                    }
                    $sheet->getRowDimension($target)->setRowHeight($sheet->getRowDimension($itemsRow)->getRowHeight());
                    foreach ($rowMerges as [$sc, $ec]) {
                        $sheet->mergeCells("{$sc}{$target}:{$ec}{$target}");
                    }
                    $inserted++;
                }
                $item = $items[$i];
                $phrase = collect($item->factors ?? [])->map(function ($f) {
                    $label = $f['label'] ?? ($f['key'] ?? '-');
                    $value = (float)($f['value'] ?? 0);
                    return $label . ' x ' . $value;
                })->join(' × ');
                $colNo = $itemsCol ?? $startCol;
                $sheet->setCellValueByColumnAndRow($colNo, $target, $i + 1);
                $sheet->setCellValueByColumnAndRow($colNo + 1, $target, $item->label);
                $sheet->setCellValueByColumnAndRow($colNo + 2, $target, $phrase);
                $sheet->getCellByColumnAndRow($colNo + 3, $target)->setValueExplicit((float) $item->unit_price, DataType::TYPE_NUMERIC);
                $sheet->getCellByColumnAndRow($colNo + 4, $target)->setValueExplicit((float) $item->subtotal, DataType::TYPE_NUMERIC);
            }
            for ($col = $startCol; $col <= $lastColIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $itemsRow);
                $v = $cell->getValue();
                $t = is_string($v) ? trim($v) : '';
                if ($t === '[[ITEMS]]') { $cell->setValue(''); }
            }
        } else {
            for ($col = $startCol; $col <= $lastColIndex; $col++) {
                $sheet->getCellByColumnAndRow($col, $itemsRow)->setValue('');
            }
        }

        return $inserted;
    }

    private function clearRemainingPlaceholders($sheet, int $startRow, int $endRow, int $startCol, int $endCol): void
    {
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = $startCol; $col <= $endCol; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                if (is_string($val) && preg_match('/\[\[.*\]\]/', $val)) {
                    $cell->setValue('');
                }
            }
        }
    }

    private function stream(Spreadsheet $spreadsheet): Response
    {
        $tmp = tempnam(sys_get_temp_dir(), 'rab_multi_');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tmp);
        return response()->download($tmp, $this->filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
