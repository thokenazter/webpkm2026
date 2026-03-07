<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Services\LpjDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LpjDocumentController extends Controller
{
    protected $documentService;
    
    public function __construct(LpjDocumentService $documentService)
    {
        $this->documentService = $documentService;
    }
    
    /**
     * Generate and download LPJ document
     */
    public function download(Request $request, Lpj $lpj)
    {
        $this->authorize('download', $lpj);
        try {
            // Generate document
            $filePath = $this->documentService->generateDocument($lpj);
            $fullPath = storage_path('app/public/' . $filePath);
            
            // Check if file exists
            if (!file_exists($fullPath)) {
                return back()->with('error', 'Gagal membuat dokumen LPJ.');
            }
            
            // Generate download filename with format: {type} {no_surat} {kegiatan}.docx
            $downloadName = "{$lpj->type} {$lpj->no_surat} {$lpj->kegiatan}.docx";
            $downloadName = preg_replace('/[^A-Za-z0-9\-_.\s]/', '_', $downloadName);
            
            // Return file download
            return response()->download($fullPath, $downloadName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])->deleteFileAfterSend(false); // Keep file for future downloads
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Preview document (optional - for future enhancement)
     */
    public function preview(Request $request, Lpj $lpj)
    {
        $this->authorize('preview', $lpj);
        try {
            $filePath = $this->documentService->generateDocument($lpj);
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (!file_exists($fullPath)) {
                return back()->with('error', 'Gagal membuat dokumen LPJ.');
            }
            
            // Return file for preview in browser
            return response()->file($fullPath, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Regenerate document (if template is updated)
     */
    public function regenerate(Request $request, Lpj $lpj)
    {
        $this->authorize('regenerate', $lpj);
        try {
            // Delete old document if exists
            $oldFiles = Storage::disk('public')->files('lpj-documents');
            foreach ($oldFiles as $file) {
                if (strpos($file, $lpj->no_surat) !== false) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            // Generate new document
            $filePath = $this->documentService->generateDocument($lpj);
            
            return back()->with('success', 'Dokumen LPJ berhasil dibuat ulang.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat ulang dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple LPJ documents as a ZIP
     */
    public function downloadMultiple(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Lpj::class);
        $idsParam = $request->get('ids', '');
        if (is_array($idsParam)) {
            $ids = array_filter(array_map('intval', $idsParam));
        } else {
            $ids = array_filter(array_map('intval', explode(',', (string) $idsParam)));
        }

        $ids = array_values(array_unique($ids));
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada dokumen untuk diunduh.');
        }

        // Safe check without triggering autoload or fatal on missing ext-zip
        if (!extension_loaded('zip') || !class_exists('\\ZipArchive')) {
            return back()->with('error', 'Fitur ZIP tidak tersedia (ext-zip belum terpasang).');
        }

        $zip = new ZipArchive();
        $zipFileName = 'Dokumen_LPJ_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        // Pastikan folder tmp ada
        if (!is_dir(dirname($zipPath))) {
            @mkdir(dirname($zipPath), 0775, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat arsip ZIP.');
        }

        $user = $request->user();
        $isAdmin = $user && ($user->isSuperAdmin() || $user->email === 'admin@admin.com');

        // Fetch only LPJs user is allowed to access
        $lpjs = Lpj::whereIn('id', $ids)
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))
            ->get();

        $added = 0;
        foreach ($lpjs as $lpj) {
            try {
                $filePath = $this->documentService->generateDocument($lpj);
                $fullPath = storage_path('app/public/' . $filePath);
                if (!file_exists($fullPath)) continue;

                $downloadName = "{$lpj->type} {$lpj->no_surat} {$lpj->kegiatan}.docx";
                $downloadName = preg_replace('/[^A-Za-z0-9\-_.\s]/', '_', $downloadName);

                $zip->addFile($fullPath, $downloadName);
                $added++;
            } catch (\Exception $e) {
                // Skip errors for individual docs
            }
        }

        $zip->close();

        if ($added === 0 || !file_exists($zipPath)) {
            return back()->with('error', 'Tidak ada dokumen yang berhasil ditambahkan ke ZIP.');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
