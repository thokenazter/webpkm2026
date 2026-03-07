<?php

namespace App\Services;

use App\Models\Lpj;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Helpers\TerbilangHelper;
use App\Helpers\DateHelper;

class LpjDocumentService
{
    /**
     * Generate LPJ document from template
     */
    public function generateDocument(Lpj $lpj)
    {
        // Load participants to get count (include borrowed name data)
        $lpj->load(['participants.employee', 'participants.borrowedEmployee']);
        $participantCount = $lpj->participants->count();
        
        // Load template based on LPJ type and participant count
        $templatePath = $this->getTemplatePath($lpj->type, $participantCount);
        
        // Get full path to template
        $fullTemplatePath = storage_path('app/' . $templatePath);
        
        if (!file_exists($fullTemplatePath)) {
            throw new \Exception("Template file not found: {$fullTemplatePath}");
        }
        
        // Create template processor
        $templateProcessor = new TemplateProcessor($fullTemplatePath);
        
        // Replace template variables
        $this->replaceTemplateVariables($templateProcessor, $lpj);
        
        // Generate filename
        $filename = $this->generateFilename($lpj);
        $outputPath = "lpj-documents/{$filename}";
        
        // Save the document
        $templateProcessor->saveAs(storage_path('app/public/' . $outputPath));
        
        return $outputPath;
    }
    
    /**
     * Get template path based on LPJ type and participant count
     */
    private function getTemplatePath($type, $participantCount = 1)
    {
        // Primary template based on type and participant count
        $primaryTemplate = "{$type}{$participantCount}.docx";
        
        // Fallback templates
        $fallbackTemplates = [
            "{$type}.docx",                              // Generic type template
            "template_{$type}.docx",                     // Old format
            "template_default.docx"                      // Default template
        ];
        
        // Get templates directory path
        $templatesPath = storage_path('app/templates');
        
        // Check if primary template exists using file_exists
        $primaryTemplatePath = $templatesPath . '/' . $primaryTemplate;
        if (file_exists($primaryTemplatePath)) {
            return 'templates/' . $primaryTemplate;
        }
        
        // Check fallback templates
        foreach ($fallbackTemplates as $template) {
            $templatePath = $templatesPath . '/' . $template;
            if (file_exists($templatePath)) {
                return 'templates/' . $template;
            }
        }
        
        // Get list of available files for error message
        $availableFiles = [];
        if (is_dir($templatesPath)) {
            $files = scandir($templatesPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'docx') {
                    $availableFiles[] = $file;
                }
            }
        }
        
        // If no template found, throw exception with helpful message
        $availableFilesStr = empty($availableFiles) ? 'Tidak ada file .docx' : implode(', ', $availableFiles);
        throw new \Exception("Template tidak ditemukan. Harap upload template: {$type}{$participantCount}.docx ke folder storage/app/templates/. File tersedia: " . $availableFilesStr);
    }
    
    /**
     * Replace template variables with actual data
     */
    private function replaceTemplateVariables(TemplateProcessor $templateProcessor, Lpj $lpj)
    {
        // Load participants
        $lpj->load(['participants.employee', 'participants.borrowedEmployee']);
        
        // Basic LPJ information
        $templateProcessor->setValue('NO_SURAT', $lpj->no_surat);
        $templateProcessor->setValue('TANGGAL_SURAT', $lpj->tanggal_surat);
        $templateProcessor->setValue('TANGGAL_KEGIATAN', $lpj->tanggal_kegiatan);
        $templateProcessor->setValue('KEGIATAN', $lpj->kegiatan);
        $templateProcessor->setValue('TIPE_LPJ', $lpj->type);
        $templateProcessor->setValue('TRANSPORT_MODE', $lpj->transport_mode ?? '-');
        
        // Desa information
        if ($lpj->type === 'SPPT') {
            $templateProcessor->setValue('JUMLAH_DESA', $lpj->jumlah_desa_darat);
            $templateProcessor->setValue('DESA_TUJUAN', $lpj->desa_tujuan_darat ?? '-');
        } else {
            $templateProcessor->setValue('JUMLAH_DESA', $lpj->jumlah_desa_seberang);
            $templateProcessor->setValue('DESA_TUJUAN', $lpj->desa_tujuan_seberang ?? '-');
        }
        
        // Parse tanggal kegiatan untuk mendapatkan tanggal mulai dan selesai
        $parsedDates = $this->parseTanggalKegiatan($lpj->tanggal_kegiatan);
        $templateProcessor->setValue('TANGGAL_BERANGKAT', $parsedDates['tanggal_mulai']);
        $templateProcessor->setValue('TANGGAL_KEMBALI', $parsedDates['tanggal_selesai']);
        $templateProcessor->setValue('TANGGAL_MULAI', $parsedDates['tanggal_mulai']);
        $templateProcessor->setValue('TANGGAL_SELESAI', $parsedDates['tanggal_selesai']);
        
        // Individual participant data (for specific templates)
        $this->replaceIndividualParticipants($templateProcessor, $lpj);
        
        // Participants table (for generic templates)
        $this->replaceParticipantsTable($templateProcessor, $lpj);
        
        // Financial summary
        $this->replaceFinancialSummary($templateProcessor, $lpj);
        
        // Current date and user
        $templateProcessor->setValue('TANGGAL_CETAK', DateHelper::formatIndonesian(now()));
        $templateProcessor->setValue('USER_NAME', auth()->user()->name ?? 'System');
    }
    
    /**
     * Replace individual participant data (for specific count templates)
     */
    private function replaceIndividualParticipants(TemplateProcessor $templateProcessor, Lpj $lpj)
    {
        $participants = $lpj->participants;
        
        // Replace individual participant data for specific templates
        foreach ($participants as $index => $participant) {
            $participantNumber = $index + 1;

            $documentEmployee = $participant->document_employee ?? $participant->employee;
            $employeeName = $documentEmployee?->nama ?? '';
            $employeeNip = $documentEmployee?->nip ?? '';
            $employeePangkat = $documentEmployee?->pangkat_golongan ?? '';
            $employeeJabatan = $documentEmployee?->jabatan ?? '';

            // Basic participant info
            $templateProcessor->setValue("PESERTA{$participantNumber}_NAMA", $employeeName);
            $templateProcessor->setValue("PESERTA{$participantNumber}_NIP", $employeeNip);
            $templateProcessor->setValue("PESERTA{$participantNumber}_PANGKAT", $employeePangkat);
            $templateProcessor->setValue("PESERTA{$participantNumber}_JABATAN", $employeeJabatan);
            $templateProcessor->setValue("PESERTA{$participantNumber}_ROLE", $participant->role);

            // Add tanggal lahir with multiple formats
            $tanggalLahir = $documentEmployee?->tanggal_lahir;
            if ($tanggalLahir) {
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR", $tanggalLahir->format('d/m/Y'));
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR_INDO", DateHelper::formatIndonesian($tanggalLahir));
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR_LONG", DateHelper::formatIndonesian($tanggalLahir));
            } else {
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR", '-');
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR_INDO", '-');
                $templateProcessor->setValue("PESERTA{$participantNumber}_TANGGAL_LAHIR_LONG", '-');
            }
            
            // Financial data
            $lama = (int) ($participant->lama_tugas_hari ?? 0);
            if ($lama <= 0) {
                $lama = \App\Helpers\DateHelper::countActivityDays((string) $lpj->tanggal_kegiatan) ?: 0;
            }
            $templateProcessor->setValue("PESERTA{$participantNumber}_LAMA_TUGAS", ($lama > 0 ? $lama : 0) . ' hari');
            $templateProcessor->setValue("PESERTA{$participantNumber}_TRANSPORT", 'Rp ' . number_format($participant->transport_amount, 0, ',', '.'));
            $templateProcessor->setValue("PESERTA{$participantNumber}_UANG_HARIAN", 'Rp ' . number_format($participant->per_diem_amount, 0, ',', '.'));
            $templateProcessor->setValue("PESERTA{$participantNumber}_TOTAL", 'Rp ' . number_format($participant->total_amount, 0, ',', '.'));
            
            // Terbilang untuk setiap peserta (lowercase)
            $templateProcessor->setValue("PESERTA{$participantNumber}_TRANSPORT_TERBILANG", TerbilangHelper::rupiah($participant->transport_amount));
            $templateProcessor->setValue("PESERTA{$participantNumber}_UANG_HARIAN_TERBILANG", TerbilangHelper::rupiah($participant->per_diem_amount));
            $templateProcessor->setValue("PESERTA{$participantNumber}_TOTAL_TERBILANG", TerbilangHelper::rupiah($participant->total_amount));
            
            // Terbilang untuk setiap peserta (Title Case)
            $templateProcessor->setValue("PESERTA{$participantNumber}_TRANSPORT_TERBILANG_TITLE", TerbilangHelper::rupiahTitle($participant->transport_amount));
            $templateProcessor->setValue("PESERTA{$participantNumber}_UANG_HARIAN_TERBILANG_TITLE", TerbilangHelper::rupiahTitle($participant->per_diem_amount));
            $templateProcessor->setValue("PESERTA{$participantNumber}_TOTAL_TERBILANG_TITLE", TerbilangHelper::rupiahTitle($participant->total_amount));

            // Additional aliases for flexibility
            $templateProcessor->setValue("NAMA_PESERTA{$participantNumber}", $employeeName);
            $templateProcessor->setValue("NIP_PESERTA{$participantNumber}", $employeeNip);
            $templateProcessor->setValue("PANGKAT_PESERTA{$participantNumber}", $employeePangkat);
            $templateProcessor->setValue("JABATAN_PESERTA{$participantNumber}", $employeeJabatan);
            
            // Tanggal lahir aliases
            if ($tanggalLahir) {
                $templateProcessor->setValue("TANGGAL_LAHIR_PESERTA{$participantNumber}", $tanggalLahir->format('d/m/Y'));
                $templateProcessor->setValue("TGL_LAHIR_PESERTA{$participantNumber}", $tanggalLahir->format('d/m/Y'));
            } else {
                $templateProcessor->setValue("TANGGAL_LAHIR_PESERTA{$participantNumber}", '-');
                $templateProcessor->setValue("TGL_LAHIR_PESERTA{$participantNumber}", '-');
            }
            
            // Terbilang aliases (lowercase)
            $templateProcessor->setValue("TRANSPORT_TERBILANG_PESERTA{$participantNumber}", TerbilangHelper::rupiah($participant->transport_amount));
            $templateProcessor->setValue("UANG_HARIAN_TERBILANG_PESERTA{$participantNumber}", TerbilangHelper::rupiah($participant->per_diem_amount));
            $templateProcessor->setValue("TOTAL_TERBILANG_PESERTA{$participantNumber}", TerbilangHelper::rupiah($participant->total_amount));
            
            // Terbilang aliases (Title Case)
            $templateProcessor->setValue("TRANSPORT_TERBILANG_TITLE_PESERTA{$participantNumber}", TerbilangHelper::rupiahTitle($participant->transport_amount));
            $templateProcessor->setValue("UANG_HARIAN_TERBILANG_TITLE_PESERTA{$participantNumber}", TerbilangHelper::rupiahTitle($participant->per_diem_amount));
            $templateProcessor->setValue("TOTAL_TERBILANG_TITLE_PESERTA{$participantNumber}", TerbilangHelper::rupiahTitle($participant->total_amount));
        }
        
        // Fill empty slots if template expects more participants than actual
        $maxParticipants = 10; // Adjust based on your maximum expected participants
        for ($i = $participants->count() + 1; $i <= $maxParticipants; $i++) {
            $templateProcessor->setValue("PESERTA{$i}_NAMA", '');
            $templateProcessor->setValue("PESERTA{$i}_NIP", '');
            $templateProcessor->setValue("PESERTA{$i}_PANGKAT", '');
            $templateProcessor->setValue("PESERTA{$i}_JABATAN", '');
            $templateProcessor->setValue("PESERTA{$i}_TANGGAL_LAHIR", '');
            $templateProcessor->setValue("PESERTA{$i}_TANGGAL_LAHIR_INDO", '');
            $templateProcessor->setValue("PESERTA{$i}_TANGGAL_LAHIR_LONG", '');
            
            // Aliases for empty slots
            $templateProcessor->setValue("NAMA_PESERTA{$i}", '');
            $templateProcessor->setValue("NIP_PESERTA{$i}", '');
            $templateProcessor->setValue("PANGKAT_PESERTA{$i}", '');
            $templateProcessor->setValue("JABATAN_PESERTA{$i}", '');
            $templateProcessor->setValue("TANGGAL_LAHIR_PESERTA{$i}", '');
            $templateProcessor->setValue("TGL_LAHIR_PESERTA{$i}", '');
            $templateProcessor->setValue("PESERTA{$i}_ROLE", '');
            $templateProcessor->setValue("PESERTA{$i}_LAMA_TUGAS", '');
            $templateProcessor->setValue("PESERTA{$i}_TRANSPORT", '');
            $templateProcessor->setValue("PESERTA{$i}_UANG_HARIAN", '');
            $templateProcessor->setValue("PESERTA{$i}_TOTAL", '');
            
            // Terbilang empty slots (lowercase)
            $templateProcessor->setValue("PESERTA{$i}_TRANSPORT_TERBILANG", '');
            $templateProcessor->setValue("PESERTA{$i}_UANG_HARIAN_TERBILANG", '');
            $templateProcessor->setValue("PESERTA{$i}_TOTAL_TERBILANG", '');
            
            // Terbilang empty slots (Title Case)
            $templateProcessor->setValue("PESERTA{$i}_TRANSPORT_TERBILANG_TITLE", '');
            $templateProcessor->setValue("PESERTA{$i}_UANG_HARIAN_TERBILANG_TITLE", '');
            $templateProcessor->setValue("PESERTA{$i}_TOTAL_TERBILANG_TITLE", '');
            
            // Aliases
            $templateProcessor->setValue("NAMA_PESERTA{$i}", '');
            $templateProcessor->setValue("NIP_PESERTA{$i}", '');
            $templateProcessor->setValue("PANGKAT_PESERTA{$i}", '');
            $templateProcessor->setValue("JABATAN_PESERTA{$i}", '');
            
            // Terbilang aliases (lowercase)
            $templateProcessor->setValue("TRANSPORT_TERBILANG_PESERTA{$i}", '');
            $templateProcessor->setValue("UANG_HARIAN_TERBILANG_PESERTA{$i}", '');
            $templateProcessor->setValue("TOTAL_TERBILANG_PESERTA{$i}", '');
            
            // Terbilang aliases (Title Case)
            $templateProcessor->setValue("TRANSPORT_TERBILANG_TITLE_PESERTA{$i}", '');
            $templateProcessor->setValue("UANG_HARIAN_TERBILANG_TITLE_PESERTA{$i}", '');
            $templateProcessor->setValue("TOTAL_TERBILANG_TITLE_PESERTA{$i}", '');
        }
    }
    
    /**
     * Replace participants table in template (for generic templates with table)
     */
    private function replaceParticipantsTable(TemplateProcessor $templateProcessor, Lpj $lpj)
    {
        $participants = $lpj->participants;
        
        // Check if template has table structure
        try {
            // Clone table row for each participant
            $templateProcessor->cloneRow('PESERTA_NO', $participants->count());
            
            foreach ($participants as $index => $participant) {
                $rowIndex = $index + 1;
                
                $documentEmployee = $participant->document_employee ?? $participant->employee;
                $templateProcessor->setValue("PESERTA_NO#{$rowIndex}", $rowIndex);
                $templateProcessor->setValue("PESERTA_NAMA#{$rowIndex}", $documentEmployee?->nama ?? '');
                $templateProcessor->setValue("PESERTA_NIP#{$rowIndex}", $documentEmployee?->nip ?? '');
                $templateProcessor->setValue("PESERTA_PANGKAT#{$rowIndex}", $documentEmployee?->pangkat_golongan ?? '');
                $templateProcessor->setValue("PESERTA_JABATAN#{$rowIndex}", $documentEmployee?->jabatan ?? '');
                $templateProcessor->setValue("PESERTA_ROLE#{$rowIndex}", $participant->role);

                // Add tanggal lahir for table
                $tanggalLahir = $documentEmployee?->tanggal_lahir;
                $templateProcessor->setValue("PESERTA_TANGGAL_LAHIR#{$rowIndex}", 
                    $tanggalLahir ? $tanggalLahir->format('d/m/Y') : '-'
                );
                $templateProcessor->setValue("PESERTA_TGL_LAHIR#{$rowIndex}", 
                    $tanggalLahir ? $tanggalLahir->format('d/m/Y') : '-'
                );
                $lama = (int) ($participant->lama_tugas_hari ?? 0);
                if ($lama <= 0) {
                    $lama = \App\Helpers\DateHelper::countActivityDays((string) $lpj->tanggal_kegiatan) ?: 0;
                }
                $templateProcessor->setValue("PESERTA_LAMA_TUGAS#{$rowIndex}", ($lama > 0 ? $lama : 0) . ' hari');
                $templateProcessor->setValue("PESERTA_TRANSPORT#{$rowIndex}", 'Rp ' . number_format($participant->transport_amount, 0, ',', '.'));
                $templateProcessor->setValue("PESERTA_UANG_HARIAN#{$rowIndex}", 'Rp ' . number_format($participant->per_diem_amount, 0, ',', '.'));
                $templateProcessor->setValue("PESERTA_TOTAL#{$rowIndex}", 'Rp ' . number_format($participant->total_amount, 0, ',', '.'));
            }
        } catch (\Exception $e) {
            // Template doesn't have table structure, skip table processing
            // This is normal for individual participant templates
        }
    }
    
    /**
     * Replace financial summary in template
     */
    private function replaceFinancialSummary(TemplateProcessor $templateProcessor, Lpj $lpj)
    {
        $totalTransport = $lpj->participants->sum('transport_amount');
        $totalPerDiem = $lpj->participants->sum('per_diem_amount');
        $grandTotal = $lpj->participants->sum('total_amount');
        
        $templateProcessor->setValue('TOTAL_TRANSPORT', 'Rp ' . number_format($totalTransport, 0, ',', '.'));
        $templateProcessor->setValue('TOTAL_UANG_HARIAN', 'Rp ' . number_format($totalPerDiem, 0, ',', '.'));
        $templateProcessor->setValue('GRAND_TOTAL', 'Rp ' . number_format($grandTotal, 0, ',', '.'));
        
        // Terbilang menggunakan helper (normal dan title case)
        $templateProcessor->setValue('GRAND_TOTAL_TERBILANG', TerbilangHelper::rupiah($grandTotal));
        $templateProcessor->setValue('GRAND_TOTAL_TERBILANG_TITLE', TerbilangHelper::rupiahTitleCase($grandTotal));
    }
    
    /**
     * Generate filename for the document
     */
    private function generateFilename(Lpj $lpj)
    {
        $date = now()->format('Y-m-d');
        $cleanType = preg_replace('/[^A-Za-z0-9\-]/', '_', $lpj->type);
        $cleanNoSurat = preg_replace('/[^A-Za-z0-9\-]/', '_', $lpj->no_surat);
        $cleanKegiatan = preg_replace('/[^A-Za-z0-9\-]/', '_', $lpj->kegiatan);
        
        return "{$cleanType}_{$cleanNoSurat}_{$cleanKegiatan}_{$date}.docx";
    }
    
    /**
     * Convert number to words (Indonesian)
     * Complete implementation for Indonesian number to words conversion
     */
    private function numberToWords($number)
    {
        if ($number == 0) {
            return 'nol';
        }
        
        $number = abs($number); // Handle negative numbers
        
        // Array untuk angka dasar
        $ones = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
            'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
        ];
        
        $tens = [
            '', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh',
            'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'
        ];
        
        // Fungsi untuk konversi ratusan
        $convertHundreds = function($num) use ($ones, $tens) {
            $result = '';
            
            if ($num >= 100) {
                $hundreds = intval($num / 100);
                if ($hundreds == 1) {
                    $result .= 'seratus';
                } else {
                    $result .= $ones[$hundreds] . ' ratus';
                }
                $num %= 100;
                if ($num > 0) $result .= ' ';
            }
            
            if ($num >= 20) {
                $tensDigit = intval($num / 10);
                $result .= $tens[$tensDigit];
                $num %= 10;
                if ($num > 0) $result .= ' ';
            }
            
            if ($num > 0) {
                if ($num < 20) {
                    $result .= $ones[$num];
                }
            }
            
            return $result;
        };
        
        // Handle different ranges
        if ($number < 1000) {
            return $convertHundreds($number);
        }
        
        if ($number < 1000000) {
            $thousands = intval($number / 1000);
            $remainder = $number % 1000;
            
            $result = '';
            if ($thousands == 1) {
                $result = 'seribu';
            } else {
                $result = $convertHundreds($thousands) . ' ribu';
            }
            
            if ($remainder > 0) {
                $result .= ' ' . $convertHundreds($remainder);
            }
            
            return $result;
        }
        
        if ($number < 1000000000) {
            $millions = intval($number / 1000000);
            $remainder = $number % 1000000;
            
            $result = $convertHundreds($millions) . ' juta';
            
            if ($remainder > 0) {
                if ($remainder >= 1000) {
                    $thousands = intval($remainder / 1000);
                    $hundreds = $remainder % 1000;
                    
                    if ($thousands == 1) {
                        $result .= ' seribu';
                    } else {
                        $result .= ' ' . $convertHundreds($thousands) . ' ribu';
                    }
                    
                    if ($hundreds > 0) {
                        $result .= ' ' . $convertHundreds($hundreds);
                    }
                } else {
                    $result .= ' ' . $convertHundreds($remainder);
                }
            }
            
            return $result;
        }
        
        if ($number < 1000000000000) {
            $billions = intval($number / 1000000000);
            $remainder = $number % 1000000000;
            
            $result = $convertHundreds($billions) . ' miliar';
            
            if ($remainder > 0) {
                if ($remainder >= 1000000) {
                    $millions = intval($remainder / 1000000);
                    $result .= ' ' . $convertHundreds($millions) . ' juta';
                    $remainder %= 1000000;
                }
                
                if ($remainder >= 1000) {
                    $thousands = intval($remainder / 1000);
                    if ($thousands == 1) {
                        $result .= ' seribu';
                    } else {
                        $result .= ' ' . $convertHundreds($thousands) . ' ribu';
                    }
                    $remainder %= 1000;
                }
                
                if ($remainder > 0) {
                    $result .= ' ' . $convertHundreds($remainder);
                }
            }
            
            return $result;
        }
        
        // For numbers larger than trillion, return formatted number
        return number_format($number, 0, ',', '.');
    }
    
    /**
     * Parse tanggal kegiatan untuk mendapatkan tanggal mulai dan selesai
     */
    private function parseTanggalKegiatan($tanggalKegiatan)
    {
        if (empty($tanggalKegiatan)) {
            return [
                'tanggal_mulai' => '-',
                'tanggal_selesai' => '-'
            ];
        }
        
        // Normalize input - remove extra spaces
        $input = trim($tanggalKegiatan);

        // Prefer centralized parser that supports lists and ranges
        try {
            $range = \App\Helpers\DateHelper::parseDateRange($input);
            if (!empty($range)) {
                $start = $range['start'] ?? null;
                $end = $range['end'] ?? null;
                if (!$start && !empty($range['days']) && is_array($range['days'])) {
                    $start = $range['days'][0] ?? null;
                    $end = end($range['days']) ?: $start;
                }
                if ($start && !$end) { $end = $start; }
                if ($start) {
                    return [
                        'tanggal_mulai' => \App\Helpers\DateHelper::formatIndonesian($start),
                        'tanggal_selesai' => \App\Helpers\DateHelper::formatIndonesian($end ?? $start),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // fallback to regex patterns below
        }
        
        // Pattern 1: "22 s/d 24 Agustus 2025" atau "22-24 Agustus 2025"
        if (preg_match('/(\d{1,2})\s*(?:s\/d|sd|-|sampai|hingga)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/i', $input, $matches)) {
            $tanggalMulai = $matches[1];
            $tanggalSelesai = $matches[2];
            $bulan = $matches[3];
            $tahun = $matches[4];
            
            return [
                'tanggal_mulai' => $tanggalMulai . ' ' . $bulan . ' ' . $tahun,
                'tanggal_selesai' => $tanggalSelesai . ' ' . $bulan . ' ' . $tahun
            ];
        }
        
        // Pattern 2: "22 Agustus s/d 24 September 2025" (beda bulan)
        if (preg_match('/(\d{1,2})\s+(\w+)\s*(?:s\/d|sd|-|sampai|hingga)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/i', $input, $matches)) {
            $tanggalMulai = $matches[1];
            $bulanMulai = $matches[2];
            $tanggalSelesai = $matches[3];
            $bulanSelesai = $matches[4];
            $tahun = $matches[5];
            
            return [
                'tanggal_mulai' => $tanggalMulai . ' ' . $bulanMulai . ' ' . $tahun,
                'tanggal_selesai' => $tanggalSelesai . ' ' . $bulanSelesai . ' ' . $tahun
            ];
        }
        
        // Pattern 3: "22 Agustus 2025 s/d 24 September 2025" (beda bulan dan tahun)
        if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})\s*(?:s\/d|sd|-|sampai|hingga)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/i', $input, $matches)) {
            $tanggalMulai = $matches[1];
            $bulanMulai = $matches[2];
            $tahunMulai = $matches[3];
            $tanggalSelesai = $matches[4];
            $bulanSelesai = $matches[5];
            $tahunSelesai = $matches[6];
            
            return [
                'tanggal_mulai' => $tanggalMulai . ' ' . $bulanMulai . ' ' . $tahunMulai,
                'tanggal_selesai' => $tanggalSelesai . ' ' . $bulanSelesai . ' ' . $tahunSelesai
            ];
        }
        
        // Pattern 4: Single date "22 Agustus 2025" (same start and end date)
        if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})/i', $input, $matches)) {
            $tanggal = $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
            
            return [
                'tanggal_mulai' => $tanggal,
                'tanggal_selesai' => $tanggal
            ];
        }
        
        // Pattern 5: "Minggu ke-2 Maret 2025" atau format deskriptif lainnya
        // Untuk format yang tidak bisa diparsing, return as is
        return [
            'tanggal_mulai' => $input,
            'tanggal_selesai' => $input
        ];
    }
}
