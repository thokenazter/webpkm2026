<?php

namespace App\Http\Controllers;

use App\Http\Requests\TibaBerangkatRequest;
use App\Models\TibaBerangkat;
use App\Models\PejabatTtd;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Helpers\DateHelper;

class TibaBerangkatController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\TibaBerangkat::class);

        $user = $request->user();
        $isAdmin = $user && ($user->isSuperAdmin() || $user->email === 'admin@admin.com');
        $q = trim((string) $request->get('q', ''));
        $tibaBerangkats = TibaBerangkat::with('details.pejabatTtd')
            ->when(!$isAdmin, fn($query) => $query->where('created_by', $user->id))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $like = '%' . $q . '%';
                    $sub->where('no_surat', 'like', $like)
                        ->orWhereHas('details.pejabatTtd', function ($qq) use ($like) {
                            $qq->where('desa', 'like', $like)
                               ->orWhere('nama', 'like', $like)
                               ->orWhere('jabatan', 'like', $like);
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['q' => $q]);
        return view('tiba-berangkats.index', compact('tibaBerangkats', 'q'));
    }

    public function create(Request $request)
    {
        $prefillDesas = [];
        $fromLpjId = $request->query('from_lpj');
        if ($fromLpjId) {
            $lpj = \App\Models\Lpj::find($fromLpjId);
            if ($lpj) {
                $this->authorize('view', $lpj);
                $service = new \App\Services\TibaBerangkatService();
                if ($lpj->type === 'SPPT') {
                    $prefillDesas = $service->parseDesaList($lpj->desa_tujuan_darat ?? '');
                } else {
                    $prefillDesas = $service->parseDesaList($lpj->desa_tujuan_seberang ?? '');
                }
            }
        }

        $pejabatTtds = PejabatTtd::orderBy('desa')->orderBy('nama')->get();
        return view('tiba-berangkats.create', compact('pejabatTtds', 'prefillDesas'));
    }

    public function store(TibaBerangkatRequest $request)
    {
        $this->authorize('create', \App\Models\TibaBerangkat::class);
        $tibaBerangkat = TibaBerangkat::create([
            'no_surat' => $request->no_surat,
            'created_by' => $request->user()->id,
        ]);

        foreach ($request->desa as $desaData) {
            $tibaBerangkat->details()->create([
                'pejabat_ttd_id' => $desaData['pejabat_ttd_id'],
                'tanggal_kunjungan' => $desaData['tanggal_kunjungan'],
            ]);
        }

        return redirect()->route('tiba-berangkats.index')
            ->with('success', 'Tiba Berangkat berhasil ditambahkan.')
            ->with('show_download', $tibaBerangkat->id);
    }

    public function show(Request $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('view', $tibaBerangkat);
        $tibaBerangkat->load('details.pejabatTtd');
        return view('tiba-berangkats.show', compact('tibaBerangkat'));
    }

    public function edit(Request $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('update', $tibaBerangkat);
        $tibaBerangkat->load('details.pejabatTtd');
        $pejabatTtds = PejabatTtd::orderBy('desa')->orderBy('nama')->get();
        return view('tiba-berangkats.edit', compact('tibaBerangkat', 'pejabatTtds'));
    }

    public function update(TibaBerangkatRequest $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('update', $tibaBerangkat);
        $tibaBerangkat->update([
            'no_surat' => $request->no_surat
        ]);

        // Delete existing details
        $tibaBerangkat->details()->delete();

        // Create new details
        foreach ($request->desa as $desaData) {
            $tibaBerangkat->details()->create([
                'pejabat_ttd_id' => $desaData['pejabat_ttd_id'],
                'tanggal_kunjungan' => $desaData['tanggal_kunjungan'],
            ]);
        }

        return redirect()->route('tiba-berangkats.index')
            ->with('success', 'Tiba Berangkat berhasil diperbarui.')
            ->with('show_download', $tibaBerangkat->id);
    }

    public function destroy(Request $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('delete', $tibaBerangkat);
        $tibaBerangkat->delete();

        return redirect()->route('tiba-berangkats.index')
            ->with('success', 'Tiba Berangkat berhasil dihapus.');
    }

    /**
     * Bulk delete Tiba Berangkat
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'tiba_berangkat_ids' => 'required|array',
            'tiba_berangkat_ids.*' => 'exists:tiba_berangkat,id',
        ]);

        $deletedCount = 0;
        $errors = [];

        foreach ($request->tiba_berangkat_ids as $tbId) {
            try {
                $tb = TibaBerangkat::findOrFail($tbId);
                $this->authorize('delete', $tb);
                $tb->delete();
                $deletedCount++;
            } catch (\Throwable $e) {
                $errors[] = "TB ID {$tbId}: " . $e->getMessage();
            }
        }

        if ($deletedCount > 0) {
            $message = "Berhasil menghapus {$deletedCount} dokumen Tiba Berangkat.";
            if (!empty($errors)) {
                $message .= " Sebagian gagal dihapus: " . implode(', ', $errors);
            }
            return back()->with('success', $message);
        }

        return back()->with('error', 'Gagal menghapus: ' . implode(', ', $errors));
    }

    public function download(Request $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('download', $tibaBerangkat);
        $tibaBerangkat->load('details.pejabatTtd');

        // Sort details by date to ensure sequential order in the document
        $details = $tibaBerangkat->details->sortBy('tanggal_kunjungan')->values();

        // Pilih template berdasarkan jumlah desa terlebih dahulu (1desa.docx, 2desa.docx, dst).
        // Jika tidak tersedia, fallback ke template dinamis tibaberangkatberurutan.docx.
        $jumlahDesa = $details->count();
        $byCountTemplate = storage_path("app/templates/{$jumlahDesa}desa.docx");
        $dynamicTemplate = storage_path('app/templates/tibaberangkatberurutan.docx');

        if (file_exists($byCountTemplate)) {
            $templatePath = $byCountTemplate;
        } elseif (file_exists($dynamicTemplate)) {
            $templatePath = $dynamicTemplate;
        } else {
            return back()->with('error', "Template tidak ditemukan. Upload {$jumlahDesa}desa.docx atau tibaberangkatberurutan.docx ke folder templates.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Basic placeholders (keep both lower/upper case compatibility)
        $templateProcessor->setValue('no_surat', $tibaBerangkat->no_surat);
        $templateProcessor->setValue('NO_SURAT', $tibaBerangkat->no_surat);
        $templateProcessor->setValue('tanggal_surat', DateHelper::formatIndonesian(now()));
        $templateProcessor->setValue('TANGGAL_SURAT', DateHelper::formatIndonesian(now()));

        // Try dynamic table pattern for tibaberangkatberurutan.docx
        $usedDynamic = false;
        if (realpath($templatePath) === realpath($dynamicTemplate)) {
            try {
                // This requires placeholders in the template table: TB_NO, TB_DESA, TB_NAMA, TB_JABATAN, TB_TANGGAL
                $templateProcessor->cloneRow('TB_NO', $details->count());
                foreach ($details as $i => $detail) {
                    $row = $i + 1;
                    $templateProcessor->setValue("TB_NO#{$row}", $row);
                    $templateProcessor->setValue("TB_DESA#{$row}", $detail->pejabatTtd->desa);
                    $templateProcessor->setValue("TB_NAMA#{$row}", $detail->pejabatTtd->nama);
                    $templateProcessor->setValue("TB_JABATAN#{$row}", $detail->pejabatTtd->jabatan);
                    $templateProcessor->setValue("TB_TANGGAL#{$row}", DateHelper::formatIndonesian($detail->tanggal_kunjungan));
                }
                $usedDynamic = true;
            } catch (\Throwable $e) {
                // Fall back to legacy numbered placeholders: desa_1, kepala_desa_1, tanggal_1, etc.
            }
        }

        if (!$usedDynamic) {
            foreach ($details as $index => $detail) {
                $num = $index + 1;
                $templateProcessor->setValue("desa_{$num}", $detail->pejabatTtd->desa);
                $templateProcessor->setValue("kepala_desa_{$num}", $detail->pejabatTtd->nama);
                $templateProcessor->setValue("tanggal_{$num}", DateHelper::formatIndonesian($detail->tanggal_kunjungan));

                // Backward compatibility aliases
                $templateProcessor->setValue("pejabat_{$num}", $detail->pejabatTtd->nama);
                $templateProcessor->setValue("jabatan_{$num}", $detail->pejabatTtd->jabatan);
                $templateProcessor->setValue("tanggal_kunjungan_{$num}", DateHelper::formatIndonesian($detail->tanggal_kunjungan));
            }
        }

        // Additional: tanggal_selesai = day after last visit
        if ($details->isNotEmpty()) {
            $lastDate = $details->last()->tanggal_kunjungan->copy()->addDay();
            $templateProcessor->setValue('tanggal_selesai', DateHelper::formatIndonesian($lastDate));
            $templateProcessor->setValue('TANGGAL_SELESAI', DateHelper::formatIndonesian($lastDate));
        }

        $fileName = "Tiba_Berangkat_{$tibaBerangkat->no_surat}.docx";
        $tempPath = storage_path("app/temp/{$fileName}");

        // Ensure temp directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function getPejabatByDesa(Request $request)
    {
        $desa = $request->get('desa');
        $pejabat = PejabatTtd::where('desa', $desa)->first();
        
        return response()->json($pejabat);
    }

    /**
     * Quick update dates (and optional no_surat) from modal before bulk download
     */
    public function quickUpdate(Request $request, TibaBerangkat $tibaBerangkat)
    {
        $this->authorize('quickUpdate', $tibaBerangkat);
        $validated = $request->validate([
            'no_surat' => 'nullable|string|max:255',
            'details' => 'required|array|min:1',
            'details.*.id' => 'required|integer|exists:tiba_berangkat_detail,id',
            'details.*.tanggal_kunjungan' => 'required|date',
        ]);

        if (!empty($validated['no_surat'])) {
            $tibaBerangkat->update(['no_surat' => $validated['no_surat']]);
        }

        // Update only details belonging to this TibaBerangkat
        $detailIds = collect($validated['details'])->pluck('id')->all();
        $details = $tibaBerangkat->details()->whereIn('id', $detailIds)->get()->keyBy('id');

        foreach ($validated['details'] as $item) {
            $detail = $details->get($item['id']);
            if ($detail) {
                $detail->update([
                    'tanggal_kunjungan' => $item['tanggal_kunjungan'],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Auto-create Tiba Berangkat from a single LPJ and redirect back to LPJ index with review modal.
     */
    public function autoFromLpj(Request $request, \App\Models\Lpj $lpj)
    {
        $this->authorize('view', $lpj);
        try {
            $service = new \App\Services\TibaBerangkatService();
            $tb = $service->createFromSingleLpj($lpj);
            if ($tb) {
                return redirect()->route('lpjs.index')
                    ->with('tiba_berangkat_review_id', $tb->id)
                    ->with('tiba_berangkat_id', $tb->id)
                    ->with('success', 'Tiba Berangkat dibuat otomatis dari ' . $lpj->type . '.');
            }
            return redirect()->route('lpjs.index')->with('error', 'Gagal membuat Tiba Berangkat otomatis (desa tidak ditemukan).');
        } catch (\Throwable $e) {
            return redirect()->route('lpjs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
