<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\CustomRabTemplateGenerator;
use App\Models\Rab;

echo "=== TESTING CUSTOM PUSKESMAS TEMPLATE ===" . PHP_EOL;

try {
    // Generate custom template
    echo "Generating custom Puskesmas template..." . PHP_EOL;
    $generator = new CustomRabTemplateGenerator();
    $spreadsheet = $generator->generatePuskesmasTemplate();

    // Test with real data
    $rabs = Rab::with('items')->orderBy('komponen')->orderBy('rincian_menu')->orderBy('kegiatan')->get();
    echo "Found {$rabs->count()} RABs to export" . PHP_EOL;

    // Load template if exists
    $customTemplatePath = storage_path('app/templates/puskesmas_custom_template.xlsx');
    if (file_exists($customTemplatePath)) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($customTemplatePath);
        echo "Using existing template: {$customTemplatePath}" . PHP_EOL;
    } else {
        echo "Using generated template" . PHP_EOL;
    }

    // Find block markers
    $sheet = $spreadsheet->getActiveSheet();
    $startRow = null;
    $endRow = null;

    for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
        for ($col = 1; $col <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn()); $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            $val = $cell->getValue();
            $txt = is_string($val) ? trim($val) : '';

            if ($txt === '[[RAB_BLOCK_START]]') {
                $startRow = $row;
            } elseif ($txt === '[[RAB_BLOCK_END]]') {
                $endRow = $row;
            }

            if ($startRow && $endRow && $endRow >= $startRow) {
                break 2;
            }
        }
    }

    if ($startRow && $endRow) {
        echo "Block markers found: rows {$startRow} to {$endRow}" . PHP_EOL;

        // Test placeholder replacement with first RAB
        if ($rabs->isNotEmpty()) {
            $firstRab = $rabs->first();
            echo "Testing with RAB: {$firstRab->kegiatan}" . PHP_EOL;

            // Replace some placeholders
            $sheet->setCellValue('B13', $firstRab->komponen);
            $sheet->setCellValue('B14', $firstRab->rincian_menu);
            $sheet->setCellValue('B15', $firstRab->kegiatan);
            $sheet->setCellValue('B17', $firstRab->total);

            echo "✅ Placeholder replacement successful!" . PHP_EOL;
        }

        // Save test file
        $testPath = storage_path('app/TEST_PUSKESMAS_TEMPLATE.xlsx');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($testPath);

        echo "✅ Test template saved to: {$testPath}" . PHP_EOL;
        echo "📊 File size: " . round(filesize($testPath) / 1024, 2) . " KB" . PHP_EOL;
        echo "📏 Total rows: " . $sheet->getHighestRow() . PHP_EOL;
        echo "📏 Total columns: " . $sheet->getHighestColumn() . PHP_EOL;

    } else {
        echo "❌ Block markers not found in template!" . PHP_EOL;
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "📄 File: " . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
}