<?php

namespace App\Http\Controllers;

use App\Models\ItemOpsionalClaim;
use App\Helpers\TerbilangHelper;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class ItemOpsionalController extends Controller
{
    /**
     * Download kwitansi document for an optional item claim.
     */
    public function download(Request $request, ItemOpsionalClaim $itemOpsionalClaim)
    {
        $templatePath = storage_path('app/templates/itemopsional.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template itemopsional.docx tidak ditemukan.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $amount = (int) $itemOpsionalClaim->amount;

        // Replace placeholders — e.g. "Snack Pelaksanaan Kelas Ibu Balita"
        $kegiatan = $itemOpsionalClaim->poa->kegiatan ?? '';
        $templateProcessor->setValue('ITEM_OPSIONAL', trim($itemOpsionalClaim->label . ' ' . $kegiatan));
        $templateProcessor->setValue('BIAYA_ITEM_OPSIONAL', 'Rp ' . number_format($amount, 0, ',', '.'));
        $templateProcessor->setValue('BIAYA_ITEM_OPSIONAL_TERBILANG', TerbilangHelper::rupiahTitleCase($amount));

        // Generate temp file — e.g. "Kwitansi Snack Pelaksanaan Kelas Ibu Balita.docx"
        $kegiatan = $itemOpsionalClaim->poa->kegiatan ?? '';
        $fileName = "Kwitansi {$itemOpsionalClaim->label} {$kegiatan}.docx";
        $fileName = preg_replace('/[^A-Za-z0-9\-_.\s]/', '', $fileName);
        $fileName = preg_replace('/\s+/', ' ', trim($fileName)); // collapse multiple spaces
        $tempPath = storage_path("app/temp/{$fileName}");

        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
