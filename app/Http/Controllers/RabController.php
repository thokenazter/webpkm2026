<?php

namespace App\Http\Controllers;

use App\Exports\RabExport;
use App\Exports\RabMasterExport;
use App\Models\Rab;
use App\Models\RabItem;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\RabTemplateExporter;
use App\Services\RabMasterTemplateExporter;
use App\Services\AllRabTemplateExporter;
use App\Services\MultiRabStackedTemplateExporter;
use App\Services\CustomRabTemplateGenerator;
use App\Services\BudgetAllocationService;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class RabController extends Controller
{
    private function ensureSuperAdmin(): void
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }
    }

    public function index(Request $request)
    {
        $selectedKomponen = $request->query('komponen');
        $selectedMenu = $request->query('menu');
        $selectedKegiatan = $request->query('kegiatan');
        $searchQuery = $request->query('q');

        $base = Rab::query();
        if ($selectedKomponen) $base->where('komponen', $selectedKomponen);
        if ($selectedMenu) $base->where('rincian_menu', $selectedMenu);
        if ($selectedKegiatan) $base->where('kegiatan', $selectedKegiatan);
        if ($searchQuery) {
            $base->where(function ($query) use ($searchQuery) {
                $query->where('komponen', 'like', '%' . $searchQuery . '%')
                    ->orWhere('rincian_menu', 'like', '%' . $searchQuery . '%')
                    ->orWhere('kegiatan', 'like', '%' . $searchQuery . '%');
            });
        }

        $rabs = (clone $base)->latest()->paginate(10)->appends($request->query());

        // Filtered charts datasets
        $byComponent = (clone $base)
            ->select('komponen', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('komponen')
            ->orderByDesc(DB::raw('SUM(total)'))
            ->get();

        $byMenu = (clone $base)
            ->select('rincian_menu', DB::raw('SUM(total) as total'))
            ->groupBy('rincian_menu')
            ->orderByDesc(DB::raw('SUM(total)'))
            ->limit(10)
            ->get();

        $byKegiatan = (clone $base)
            ->select('kegiatan', DB::raw('SUM(total) as total'))
            ->groupBy('kegiatan')
            ->orderByDesc(DB::raw('SUM(total)'))
            ->limit(10)
            ->get();

        // Filter lists (dependent)
        $componentsList = Rab::select('komponen')->distinct()->orderBy('komponen')->pluck('komponen');
        $menuList = Rab::when($selectedKomponen, fn($q) => $q->where('komponen', $selectedKomponen))
            ->select('rincian_menu')->distinct()->orderBy('rincian_menu')->pluck('rincian_menu');
        $kegiatanList = Rab::when($selectedKomponen, fn($q) => $q->where('komponen', $selectedKomponen))
            ->when($selectedMenu, fn($q) => $q->where('rincian_menu', $selectedMenu))
            ->select('kegiatan')->distinct()->orderBy('kegiatan')->pluck('kegiatan');

        return view('rabs.index', compact(
            'rabs',
            'byComponent',
            'byMenu',
            'byKegiatan',
            'componentsList',
            'menuList',
            'kegiatanList',
            'selectedKomponen',
            'selectedMenu',
            'selectedKegiatan',
            'searchQuery'
        ));
    }

    public function create()
    {
        $this->ensureSuperAdmin();
        $components = \App\Models\Rab::components();
        return view('rabs.create', compact('components'));
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();
        $validated = $request->validate([
            'komponen' => ['required', 'string', 'max:255', Rule::in(array_values(Rab::components()))],
            'rab_menu_id' => ['nullable', 'integer', 'exists:rab_menus,id'],
            'rab_kegiatan_id' => ['nullable', 'integer', 'exists:rab_kegiatans,id', Rule::unique('rabs', 'rab_kegiatan_id')],
            'rincian_menu' => 'required|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'new_menu_name' => 'nullable|string|max:255',
            'new_kegiatan_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.type' => 'nullable|string|max:100',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.factors' => 'nullable|array',
            'items.*.factors.*.label' => 'nullable|string|max:100',
            'items.*.factors.*.key' => 'nullable|string|max:100',
            'items.*.factors.*.value' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $rabMenuId = $validated['rab_menu_id'] ?? null;
            $rabKegiatanId = $validated['rab_kegiatan_id'] ?? null;
            $rincianMenu = $validated['rincian_menu'];
            $kegiatan = $validated['kegiatan'];

            // Handle inline menu creation
            if (!empty($validated['new_menu_name']) && !$rabMenuId) {
                $componentKey = array_search($validated['komponen'], Rab::components());
                if ($componentKey) {
                    $newMenu = RabMenu::create([
                        'component_key' => $componentKey,
                        'name' => trim($validated['new_menu_name']),
                    ]);
                    $rabMenuId = $newMenu->id;
                    $rincianMenu = $newMenu->name;
                }
            }

            // Handle inline kegiatan creation
            if (!empty($validated['new_kegiatan_name']) && !$rabKegiatanId && $rabMenuId) {
                $newKegiatan = RabKegiatan::create([
                    'rab_menu_id' => $rabMenuId,
                    'name' => trim($validated['new_kegiatan_name']),
                ]);
                $rabKegiatanId = $newKegiatan->id;
                $kegiatan = $newKegiatan->name;
            }

            $rab = Rab::create([
                'komponen' => $validated['komponen'],
                'rab_menu_id' => $rabMenuId,
                'rab_kegiatan_id' => $rabKegiatanId,
                'rincian_menu' => $rincianMenu,
                'kegiatan' => $kegiatan,
                'total' => 0,
                'created_by' => Auth::id(),
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $factors = $item['factors'] ?? [];
                // normalize factors to ensure numeric values
                $normalized = [];
                foreach ($factors as $f) {
                    if (!is_array($f)) continue;
                    $normalized[] = [
                        'key' => $f['key'] ?? ($f['label'] ?? ''),
                        'label' => $f['label'] ?? ($f['key'] ?? ''),
                        'value' => isset($f['value']) ? (float) $f['value'] : 0,
                    ];
                }

                $rabItem = new RabItem([
                    'label' => $item['label'],
                    'type' => $item['type'] ?? null,
                    'factors' => $normalized,
                    'unit_price' => (float) $item['unit_price'],
                ]);
                $rabItem->subtotal = $rabItem->computeSubtotal();
                $rab->items()->save($rabItem);
                $total += $rabItem->subtotal;
            }

            $rab->total = $total;
            $rab->save();

            // Ensure Activity exists for this kegiatan
            $this->ensureActivityExists($rab->kegiatan);

            // Auto-create/update Budget Allocation for current year
            app(BudgetAllocationService::class)->ensureForRab($rab, (int) date('Y'));

            return redirect()->route('rabs.show', $rab)
                ->with('success', 'RAB berhasil dibuat.');
        });
    }

    public function show(Rab $rab)
    {
        $rab->load('items');
        return view('rabs.show', compact('rab'));
    }

    public function edit(Rab $rab)
    {
        $this->ensureSuperAdmin();
        $rab->load('items');
        $components = \App\Models\Rab::components();
        return view('rabs.edit', compact('rab', 'components'));
    }

    public function update(Request $request, Rab $rab)
    {
        $this->ensureSuperAdmin();
        $validated = $request->validate([
            'komponen' => ['required', 'string', 'max:255', Rule::in(array_values(Rab::components()))],
            'rab_menu_id' => ['nullable', 'integer', 'exists:rab_menus,id'],
            'rab_kegiatan_id' => ['nullable', 'integer', 'exists:rab_kegiatans,id', Rule::unique('rabs', 'rab_kegiatan_id')->ignore($rab->id)],
            'rincian_menu' => 'required|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'new_menu_name' => 'nullable|string|max:255',
            'new_kegiatan_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.type' => 'nullable|string|max:100',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.factors' => 'nullable|array',
            'items.*.factors.*.label' => 'nullable|string|max:100',
            'items.*.factors.*.key' => 'nullable|string|max:100',
            'items.*.factors.*.value' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $rab) {
            $rabMenuId = $validated['rab_menu_id'] ?? null;
            $rabKegiatanId = $validated['rab_kegiatan_id'] ?? null;
            $rincianMenu = $validated['rincian_menu'];
            $kegiatan = $validated['kegiatan'];

            // Handle inline menu creation
            if (!empty($validated['new_menu_name']) && !$rabMenuId) {
                $componentKey = array_search($validated['komponen'], Rab::components());
                if ($componentKey) {
                    $newMenu = RabMenu::create([
                        'component_key' => $componentKey,
                        'name' => trim($validated['new_menu_name']),
                    ]);
                    $rabMenuId = $newMenu->id;
                    $rincianMenu = $newMenu->name;
                }
            }

            // Handle inline kegiatan creation
            if (!empty($validated['new_kegiatan_name']) && !$rabKegiatanId && $rabMenuId) {
                $newKegiatan = RabKegiatan::create([
                    'rab_menu_id' => $rabMenuId,
                    'name' => trim($validated['new_kegiatan_name']),
                ]);
                $rabKegiatanId = $newKegiatan->id;
                $kegiatan = $newKegiatan->name;
            }

            $rab->update([
                'komponen' => $validated['komponen'],
                'rab_menu_id' => $rabMenuId,
                'rab_kegiatan_id' => $rabKegiatanId,
                'rincian_menu' => $rincianMenu,
                'kegiatan' => $kegiatan,
            ]);

            // Replace all items for simplicity
            $rab->items()->delete();

            $total = 0;
            foreach ($validated['items'] as $item) {
                $factors = $item['factors'] ?? [];
                $normalized = [];
                foreach ($factors as $f) {
                    if (!is_array($f)) continue;
                    $normalized[] = [
                        'key' => $f['key'] ?? ($f['label'] ?? ''),
                        'label' => $f['label'] ?? ($f['key'] ?? ''),
                        'value' => isset($f['value']) ? (float) $f['value'] : 0,
                    ];
                }

                $rabItem = new RabItem([
                    'label' => $item['label'],
                    'type' => $item['type'] ?? null,
                    'factors' => $normalized,
                    'unit_price' => (float) $item['unit_price'],
                ]);
                $rabItem->subtotal = $rabItem->computeSubtotal();
                $rab->items()->save($rabItem);
                $total += $rabItem->subtotal;
            }

            $rab->total = $total;
            $rab->save();

            // Ensure Activity exists for this kegiatan
            $this->ensureActivityExists($rab->kegiatan);

            // Sync Budget Allocation amount with updated RAB total (current year)
            app(BudgetAllocationService::class)->ensureForRab($rab, (int) date('Y'));

            return redirect()->route('rabs.show', $rab)
                ->with('success', 'RAB berhasil diperbarui.');
        });
    }

    private function ensureActivityExists(?string $name): void
    {
        $name = trim((string) $name);
        if ($name === '') return;
        Activity::firstOrCreate(['name' => $name], ['name' => $name]);
    }

    public function destroy(Rab $rab)
    {
        $this->ensureSuperAdmin();
        $rab->delete();
        return redirect()->route('rabs.index')->with('success', 'RAB berhasil dihapus.');
    }

    public function export(Rab $rab)
    {
        $this->ensureSuperAdmin();
        $rab->load('items');
        $filename = 'RAB_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $rab->kegiatan) . '.xlsx';
        $templatePath = resource_path('templates/templaterab.xlsx');
        if (file_exists($templatePath)) {
            return app(RabTemplateExporter::class)->download($rab, $filename);
        }
        // Fallback to generic export view
        return Excel::download(new RabExport($rab), $filename);
    }

    public function exportMaster()
    {
        $this->ensureSuperAdmin();

        // Debug: Log sebelum export
        \Log::info('Starting master RAB export...');

        $filename = 'MASTER_RAB_ALL_KEGIATAN_' . now()->format('Y-m-d_H-i') . '.xlsx';

        // Debug: Log jumlah RABs
        $totalRabs = Rab::count();
        \Log::info("Total RABs to export: {$totalRabs}");

        try {
            \Log::info('Creating RabMasterExport instance...');
            $export = new RabMasterExport();
            \Log::info('Excel download initiated...');

            return Excel::download($export, $filename);
        } catch (\Exception $e) {
            \Log::error('Error in master export: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }

    public function exportMasterTemplate()
    {
        $this->ensureSuperAdmin();

        $filename = 'MASTER_RAB_TEMPLATE_' . now()->format('Y-m-d_H-i') . '.xlsx';

        try {
            $exporter = new RabMasterTemplateExporter($filename);
            return $exporter->download();
        } catch (\Exception $e) {
            \Log::error('Error in master template export: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal export template: ' . $e->getMessage());
        }
    }

    // Export all RABs using a single-sheet custom template with repeated blocks
    public function exportAllTemplated()
    {
        $this->ensureSuperAdmin();

        $filename = 'ALL_RAB_TEMPLATED_' . now()->format('Y-m-d_H-i') . '.xlsx';
        try {
            $exporter = new AllRabTemplateExporter($filename);
            return $exporter->download();
        } catch (\Throwable $e) {
            \Log::error('Error in ALL RAB templated export: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->route('rabs.index')->with('error', 'Gagal export ALL RAB templated: ' . $e->getMessage());
        }
    }

    // Export using custom Puskesmas template
    public function exportPuskesmasTemplate()
    {
        $this->ensureSuperAdmin();

        $filename = 'REKAP_RAB_PUSKESMAS_' . now()->format('Y-m-d_H-i') . '.xlsx';

        try {
            // Use custom template
            $customTemplatePath = storage_path('app/templates/puskesmas_custom_template.xlsx');

            if (file_exists($customTemplatePath)) {
                $spreadsheet = IOFactory::load($customTemplatePath);
            } else {
                // Generate if not exists
                $generator = new CustomRabTemplateGenerator();
                $spreadsheet = $generator->generatePuskesmasTemplate();

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($customTemplatePath);
            }

            // Find the first sheet containing block markers
            [$sheet, $startRow, $endRow] = $this->locateRabBlock($spreadsheet);

            if (!$sheet) {
                return back()->with('error', 'Template tidak memiliki block markers yang valid');
            }

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

            // Determine last column on the sheet
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
                return $this->streamSpreadsheet($spreadsheet, $filename);
            }

            $currentInsertRow = $endRow + 1;

            // Fill the first (existing) block with the first RAB
            $firstRab = $rabs->shift();
            $rowDelta = $this->fillBlockForRab($sheet, $startRow, $endRow, $firstRab, $lastColLetter);
            $currentInsertRow = $endRow + 1 + $rowDelta;

            // Duplicate and fill blocks for the rest RABs
            foreach ($rabs as $index => $rab) {
                // Insert rows for a new block copy
                $sheet->insertNewRowBefore($currentInsertRow, $blockRowsCount);

                // Copy row heights, styles, and original template values
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

            return $this->streamSpreadsheet($spreadsheet, $filename);
        } catch (\Throwable $e) {
            \Log::error('Error in Puskesmas template export: ' . $e->getMessage());
            return back()->with('error', 'Gagal export template Puskesmas: ' . $e->getMessage());
        }
    }

    // Generate a standard alltemplaterab.xlsx into storage and download it
    public function generateAllTemplate()
    {
        $this->ensureSuperAdmin();

        // Build spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ALL RAB');

        // Block markers and headers
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
        $sheet->getStyle('A9:E9')->getFont()->setBold(true);

        // Items template row
        $sheet->setCellValue('A10', '[[ITEMS]]');

        // Block end
        $sheet->setCellValue('A12', '[[RAB_BLOCK_END]]');

        // Bold labels
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A3:A7')->getFont()->setBold(true);

        // Column widths
        foreach (['A' => 6, 'B' => 35, 'C' => 40, 'D' => 18, 'E' => 18] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Number format for value columns in the template row (D/E)
        $sheet->getStyle('D10:E10')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Save to storage
        try {
            $path = storage_path('app/templates/alltemplaterab.xlsx');
            @mkdir(dirname($path), 0777, true);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($path);
            return response()->download($path, 'alltemplaterab.xlsx');
        } catch (\Throwable $e) {
            \Log::error('Error generating ALL template: ' . $e->getMessage());
            return redirect()->route('rabs.index')->with('error', 'Gagal membuat alltemplaterab.xlsx: ' . $e->getMessage());
        }
    }

    // Export multiple selected RABs stacked in one sheet using templaterab.xlsx
    public function exportMultipleStacked(Request $request)
    {
        $this->ensureSuperAdmin();
        $idsParam = $request->query('ids');
        if (!$idsParam) {
            return redirect()->route('rabs.index')->with('error', 'Parameter ids wajib diisi, contoh: ?ids=1,2');
        }
        $ids = array_values(array_filter(array_map(fn($x) => (int) trim($x), explode(',', (string) $idsParam)), fn($v) => $v > 0));
        if (count($ids) === 0) {
            return redirect()->route('rabs.index')->with('error', 'Tidak ada ID RAB yang valid.');
        }
        $rabs = Rab::with('items')->whereIn('id', $ids)->get();
        $sort = strtolower((string) $request->query('sort', 'grouped'));
        if ($sort === 'manual') {
            // Preserve input order
            $byId = $rabs->keyBy('id');
            $ordered = [];
            foreach ($ids as $id) {
                if ($byId->has($id)) $ordered[] = $byId->get($id);
            }
        } else {
            // Grouped: by Komponen (spec order), then Rincian Menu, then Kegiatan
            $compList = \App\Models\Rab::components();
            $rank = [];
            $i = 0; foreach ($compList as $key => $name) { $rank[$name] = $i++; }
            $ordered = $rabs->sort(function($a, $b) use ($rank) {
                $ra = $rank[$a->komponen] ?? 9999;
                $rb = $rank[$b->komponen] ?? 9999;
                if ($ra !== $rb) return $ra <=> $rb;
                $m = strnatcasecmp((string)$a->rincian_menu, (string)$b->rincian_menu);
                if ($m !== 0) return $m;
                return strnatcasecmp((string)$a->kegiatan, (string)$b->kegiatan);
            })->values()->all();
        }
        if (count($ordered) === 0) {
            return redirect()->route('rabs.index')->with('error', 'RAB tidak ditemukan untuk ID yang diberikan.');
        }
        try {
            $exporter = new MultiRabStackedTemplateExporter($ordered, 'RAB_MULTI_' . now()->format('Y-m-d_H-i') . '.xlsx');
            return $exporter->download();
        } catch (\Throwable $e) {
            \Log::error('Error in multi-stacked export: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->route('rabs.index')->with('error', 'Gagal export multiple: ' . $e->getMessage());
        }
    }

    // Diagnose stacked template area and placeholders
    public function diagnoseStackedTemplate()
    {
        $this->ensureSuperAdmin();
        try {
            $storageTemplate = storage_path('app/templates/templaterab.xlsx');
            $resourceTemplate = resource_path('templates/templaterab.xlsx');
            $template = file_exists($storageTemplate) ? $storageTemplate : $resourceTemplate;
            if (!file_exists($template)) {
                return response()->json(['ok' => false, 'error' => 'templaterab.xlsx tidak ditemukan di storage/app/templates atau resources/templates'], 404);
            }

            $spreadsheet = IOFactory::load($template);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());

            // Find block markers [[STACK_BLOCK_START]] / [[STACK_BLOCK_END]]
            $start = null;
            $end = null;
            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 1; $col <= $highestColIndex; $col++) {
                    $val = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    $txt = is_string($val) ? trim($val) : '';
                    if ($txt === '[[STACK_BLOCK_START]]') $start = [$row, $col];
                    if ($txt === '[[STACK_BLOCK_END]]') $end = [$row, $col];
                }
            }
            $sr = 1;
            $sc = 1;
            $er = $highestRow;
            $ec = $highestColIndex;
            $usedMarkers = false;
            if ($start && $end) {
                $sr = min($start[0], $end[0]);
                $er = max($start[0], $end[0]);
                $sc = min($start[1], $end[1]);
                $ec = max($start[1], $end[1]);
                $usedMarkers = true;
            }

            // Find [[ITEMS]] inside area
            $itemsRow = null;
            $itemsCol = null;
            for ($row = $sr; $row <= $er; $row++) {
                for ($col = $sc; $col <= $ec; $col++) {
                    $val = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    $txt = is_string($val) ? trim($val) : '';
                    if ($txt === '[[ITEMS]]') {
                        $itemsRow = $row;
                        $itemsCol = $col;
                        break 2;
                    }
                }
            }

            // Collect merges fully within area
            $merges = [];
            foreach ($sheet->getMergeCells() as $range) {
                if (preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $range, $m)) {
                    $c1 = Coordinate::columnIndexFromString($m[1]);
                    $r1 = (int) $m[2];
                    $c2 = Coordinate::columnIndexFromString($m[3]);
                    $r2 = (int) $m[4];
                    if ($r1 >= $sr && $r2 <= $er && $c1 >= $sc && $c2 <= $ec) {
                        $merges[] = $range;
                    }
                }
            }

            // Scan placeholders present in area (first 200 unique tokens)
            $placeholders = [];
            for ($row = $sr; $row <= $er; $row++) {
                for ($col = $sc; $col <= $ec; $col++) {
                    $val = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    if (!is_string($val)) continue;
                    if (preg_match_all('/\[\[[A-Z0-9_]+\]\]/', $val, $m)) {
                        foreach ($m[0] as $ph) {
                            $placeholders[$ph] = true;
                            if (count($placeholders) >= 200) break 2;
                        }
                    }
                }
            }

            // Warnings
            $warnings = [];
            if (!$itemsRow) $warnings[] = 'Marker [[ITEMS]] tidak ditemukan dalam area blok.';
            if ($itemsRow && ($itemsCol + 4) > $ec) $warnings[] = 'Kolom untuk tabel items (5 kolom) tidak cukup dari posisi [[ITEMS]].';

            $resp = [
                'ok' => true,
                'template_path' => $template,
                'used_markers' => $usedMarkers,
                'block' => [
                    'start' => ['row' => $sr, 'col' => $sc, 'addr' => Coordinate::stringFromColumnIndex($sc) . $sr],
                    'end'   => ['row' => $er, 'col' => $ec, 'addr' => Coordinate::stringFromColumnIndex($ec) . $er],
                ],
                'items' => $itemsRow ? [
                    'row' => $itemsRow,
                    'col' => $itemsCol,
                    'addr' => Coordinate::stringFromColumnIndex($itemsCol) . $itemsRow,
                    'table_cols' => [
                        'no' => Coordinate::stringFromColumnIndex($itemsCol),
                        'item' => Coordinate::stringFromColumnIndex($itemsCol + 1),
                        'faktor' => Coordinate::stringFromColumnIndex($itemsCol + 2),
                        'unit_price' => Coordinate::stringFromColumnIndex($itemsCol + 3),
                        'subtotal' => Coordinate::stringFromColumnIndex($itemsCol + 4),
                    ],
                ] : null,
                'merges_in_block' => $merges,
                'placeholders_in_block' => array_keys($placeholders),
                'warnings' => $warnings,
            ];
            return response()->json($resp);
        } catch (\Throwable $e) {
            \Log::error('Diagnose template failed: ' . $e->getMessage());
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // API: Get RAB info by kegiatan name
    public function infoByKegiatan(Request $request)
    {
        $name = trim((string) $request->query('kegiatan', ''));
        if ($name === '') {
            return response()->json(['data' => null]);
        }
        $rab = Rab::where('kegiatan', $name)->latest()->with('items')->first();
        if (!$rab) {
            return response()->json(['data' => null]);
        }
        $transport = null;
        $perDiem = null;
        $orang = null;

        foreach ($rab->items as $item) {
            $label = strtolower((string) $item->label);
            $type = strtolower((string) ($item->type ?? ''));
            // Capture transport unit price (prefer items marked transport_*)
            if (str_contains($label, 'transport') || str_starts_with($type, 'transport')) {
                $transport = max($transport ?? 0, (float) $item->unit_price);
            }
            // Capture per diem / uang harian
            if (str_contains($label, 'harian') || $type === 'uang_harian') {
                $perDiem = max($perDiem ?? 0, (float) $item->unit_price);
            }
            // Find factor orang
            if (is_array($item->factors)) {
                foreach ($item->factors as $f) {
                    $flabel = strtolower((string) ($f['label'] ?? $f['key'] ?? ''));
                    if (str_contains($flabel, 'orang')) {
                        $val = (int) round((float) ($f['value'] ?? 0));
                        $orang = max($orang ?? 0, $val);
                    }
                }
            }
        }

        return response()->json([
            'data' => [
                'rab_id' => $rab->id,
                'transport_unit_price' => $transport,
                'per_diem_rate' => $perDiem,
                'orang' => $orang,
            ]
        ]);
    }

    // API: basic info by RAB id (total & estimated occurrences)
    public function basic(Rab $rab)
    {
        $occ = 0;
        $participantLimit = 0;
        foreach ($rab->items as $item) {
            if (!is_array($item->factors)) continue;
            foreach ($item->factors as $f) {
                $label = strtolower((string) ($f['label'] ?? $f['key'] ?? ''));
                $val = (int) round((float) ($f['value'] ?? 0));
                if (str_contains($label, 'kali')) {
                    $occ = max($occ, $val);
                }
                if (str_contains($label, 'orang')) {
                    $participantLimit = max($participantLimit, $val);
                }
            }
        }
        return response()->json([
            'data' => [
                'rab_id' => $rab->id,
                'kegiatan' => $rab->kegiatan,
                'total' => (float) $rab->total,
                'estimated_occurrences' => $occ,
                'participant_limit' => $participantLimit,
            ]
        ]);
    }

    // API: infer village targets (darat & seberang) from RAB items
    public function targets(Rab $rab)
    {
        $rab->loadMissing('items');
        $explicitDarat = null;      // from item.type === transport_darat
        $explicitSeberang = null;   // from item.type === transport_laut|transport_seberang
        $patternDarat = null;       // from label heuristics
        $patternSeberang = null;    // from label heuristics
        $fallbackSeberang = null;   // from uang_harian when no transport laut present

        foreach ($rab->items as $it) {
            $label = strtolower((string) $it->label);
            $type = strtolower((string) ($it->type ?? ''));
            $desa = 0;
            if (is_array($it->factors)) {
                foreach ($it->factors as $f) {
                    $fl = strtolower((string) ($f['label'] ?? $f['key'] ?? ''));
                    $fv = (int) round((float) ($f['value'] ?? 0));
                    if ($fv > 0 && str_contains($fl, 'desa')) {
                        $desa = max($desa, $fv);
                    }
                }
            }
            if ($desa <= 0) continue;

            if ($type === 'transport_darat') {
                $explicitDarat = max((int) ($explicitDarat ?? 0), $desa);
            } elseif ($type === 'transport_laut' || $type === 'transport_seberang') {
                $explicitSeberang = max((int) ($explicitSeberang ?? 0), $desa);
            } elseif ($type === 'uang_harian') {
                $fallbackSeberang = max((int) ($fallbackSeberang ?? 0), $desa);
            }

            // Label-based heuristics
            if ($patternDarat === null && (str_contains($label, 'darat'))) {
                $patternDarat = $desa;
            }
            if ($patternSeberang === null && (str_contains($label, 'laut') || str_contains($label, 'seberang'))) {
                $patternSeberang = $desa;
            }
        }

        $targetDarat = $explicitDarat ?? $patternDarat ?? 0;
        $targetSeberang = $explicitSeberang ?? $patternSeberang ?? ($explicitSeberang === null && $patternSeberang === null ? ($fallbackSeberang ?? 0) : 0);

        return response()->json([
            'data' => [
                'target_darat' => (int) $targetDarat,
                'target_seberang' => (int) $targetSeberang,
            ]
        ]);
    }

    // Helper methods for custom template processing
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

                // Build volume phrase from factors
                $volume = '';
                $satuan = '';
                if (is_array($item->factors) && !empty($item->factors)) {
                    $factors = [];
                    foreach ($item->factors as $f) {
                        $label = $f['label'] ?? ($f['key'] ?? '');
                        $value = (float)($f['value'] ?? 0);
                        if ($value > 0) {
                            $factors[] = $value . ' ' . $label;
                            if (empty($satuan)) $satuan = $label;
                        }
                    }
                    $volume = implode(' × ', $factors);
                }

                $sheet->setCellValue("A{$targetRow}", $i + 1);
                $sheet->setCellValue("B{$targetRow}", $item->label);
                $sheet->setCellValue("C{$targetRow}", $volume);
                $sheet->setCellValue("D{$targetRow}", $satuan);
                $sheet->setCellValueExplicit("E{$targetRow}", (float) $item->unit_price, DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit("F{$targetRow}", (float) $item->subtotal, DataType::TYPE_NUMERIC);
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

    private function streamSpreadsheet(Spreadsheet $spreadsheet, string $filename)
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'rab_puskesmas_');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
