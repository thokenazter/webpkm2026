<?php

namespace App\Services;

use App\Models\Rab;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class RabTemplateExporter
{
    public function download(Rab $rab, string $filename = 'RAB.xlsx'): StreamedResponse
    {
        // Prefer storage/app/templates/templaterab.xlsx then fallback to resources/templates/templaterab.xlsx
        $storageTemplate = storage_path('app/templates/templaterab.xlsx');
        $resourceTemplate = resource_path('templates/templaterab.xlsx');
        $template = file_exists($storageTemplate) ? $storageTemplate : $resourceTemplate;
        if (!file_exists($template)) {
            abort(404, 'Template RAB tidak ditemukan di storage/app/templates atau resources/templates');
        }

        $spreadsheet = IOFactory::load($template);

        // Build replacement maps
        $stringReplacements = [
            '[[KOMPONEN]]' => (string) $rab->komponen,
            '[[RINCIAN_MENU]]' => (string) $rab->rincian_menu,
            '[[KEGIATAN]]' => (string) $rab->kegiatan,
        ];
        $numericReplacements = [
            '[[TOTAL]]' => (float) $rab->total,
        ];

        // Items granular placeholders
        $itemsForPlaceholder = $rab->items()->get()->values();
        $stringReplacements['[[ITEM_COUNT]]'] = (string) $itemsForPlaceholder->count();
        foreach ($itemsForPlaceholder as $i => $item) {
            $idx = $i + 1; // 1-based
            $prefix = '[[ITEM' . $idx . '_';
            $stringReplacements[$prefix . 'LABEL]]'] = (string) $item->label;
            $numericReplacements[$prefix . 'UNIT_PRICE]]'] = (float) $item->unit_price;
            $numericReplacements[$prefix . 'SUBTOTAL]]'] = (float) $item->subtotal;

            $faktorPhrase = collect($item->factors ?? [])->map(function ($f) {
                $label = $f['label'] ?? ($f['key'] ?? '-');
                $value = (float)($f['value'] ?? 0);
                return $label . ' x ' . $value;
            })->join(' × ');
            $stringReplacements[$prefix . 'FACTOR_PHRASE]]'] = $faktorPhrase;

            $factors = collect($item->factors ?? [])->values();
            foreach ($factors as $j => $f) {
                $jdx = $j + 1;
                $label = $f['label'] ?? ($f['key'] ?? '');
                $value = (float)($f['value'] ?? 0);
                $stringReplacements[$prefix . 'FACTOR' . $jdx . '_LABEL]]'] = (string) $label;
                $numericReplacements[$prefix . 'FACTOR' . $jdx . '_VALUE]]'] = (float) $value;
            }
        }

        $itemsSheet = null;
        $itemsRow = null;
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    $text = is_string($val) ? $val : (is_null($val) ? '' : (string) $val);
                    if ($text !== '') {
                        if ($itemsSheet === null && trim($text) === '[[ITEMS]]') {
                            $itemsSheet = $sheet;
                            $itemsRow = $row;
                        }
                        $trim = trim($text);
                        if (array_key_exists($trim, $numericReplacements)) {
                            $cell->setValueExplicit($numericReplacements[$trim], DataType::TYPE_NUMERIC);
                            $text = (string) $cell->getValue();
                        } else {
                            // Allow inline replacements (including numeric placeholders rendered as plain text)
                            $inlineMap = $stringReplacements + array_map(
                                fn($n) => (string) (is_float($n) ? (0 + $n) : $n),
                                $numericReplacements
                            );
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
            }
        }

        // Fill items starting at [[ITEMS]] row if present (first occurrence across sheets)
        if ($itemsSheet && $itemsRow) {
            $sheet = $itemsSheet;
            $items = $rab->items()->get();
            $count = $items->count();
            if ($count > 0) {
                $lastCol = $sheet->getHighestColumn();
                $templateRange = "A{$itemsRow}:{$lastCol}{$itemsRow}";

                // Collect merges that belong only to the template row
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
                for ($i = 0; $i < $count; $i++) {
                    $targetRow = $itemsRow + $i;
                    if ($i > 0) {
                        $sheet->insertNewRowBefore($targetRow, 1);
                        $sheet->duplicateStyle($sheet->getStyle($templateRange), "A{$targetRow}:{$lastCol}{$targetRow}");
                        // replicate row height
                        $sheet->getRowDimension($targetRow)->setRowHeight(
                            $sheet->getRowDimension($itemsRow)->getRowHeight()
                        );
                        // replicate merges for this row
                        foreach ($rowMerges as [$sc, $ec]) {
                            $sheet->mergeCells("{$sc}{$targetRow}:{$ec}{$targetRow}");
                        }
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
                // Clean any remaining [[ITEMS]] markers in that row across all columns
                for ($col = 1; $col <= Coordinate::columnIndexFromString($lastCol); $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $itemsRow);
                    $v = $cell->getValue();
                    $t = is_string($v) ? $v : (is_null($v) ? '' : (string) $v);
                    if ($t === '[[ITEMS]]') {
                        $cell->setValue('');
                    }
                }
            } else {
                $lastCol = $sheet->getHighestColumn();
                for ($col = 1; $col <= Coordinate::columnIndexFromString($lastCol); $col++) {
                    $sheet->getCellByColumnAndRow($col, $itemsRow)->setValue('');
                }
            }
        }

        // Stream response
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
