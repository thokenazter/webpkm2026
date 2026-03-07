<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CustomRabTemplateGenerator
{
    public function generatePuskesmasTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REKAP RAB PUSKESMAS');

        // Set margins
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.3);
        $sheet->getPageMargins()->setLeft(0.3);
        $sheet->getPageMargins()->setBottom(0.5);

        // === KOPI SURAT ===
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'PEMERINTAH KABUPATEN/KOTA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'DINAS KESEHATAN');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:E3');
        $sheet->setCellValue('A3', 'PUSKESMAS [[NAMA_PUSKESMAS]]');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:E4');
        $sheet->setCellValue('A4', 'Jl. [[ALAMAT_PUSKESMAS]] - [[KOTA_PUSKESMAS]]');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // === JUDUL LAPORAN ===
        $sheet->mergeCells('A6:E6');
        $sheet->setCellValue('A6', 'REKAPITULASI RENCANA ANGGARAN BIAYA (RAB)');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A7:E7');
        $sheet->setCellValue('A7', 'TAHUN ANGGARAN [[TAHUN]]');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // === INFORMASI DOKUMEN ===
        $sheet->setCellValue('A9', 'No. Dokumen:');
        $sheet->setCellValue('B9', '[[NO_DOKUMEN]]');
        $sheet->setCellValue('D9', 'Tanggal:');
        $sheet->setCellValue('E9', '[[TANGGAL]]');

        // === RAB BLOCK START ===
        $sheet->setCellValue('A11', '[[RAB_BLOCK_START]]');
        $sheet->setCellValue('B11', 'RAB KEGIATAN: [[KEGIATAN]]');

        // === INFO RAB ===
        $sheet->setCellValue('A13', 'Komponen');
        $sheet->setCellValue('B13', '[[KOMPONEN]]');
        $sheet->getStyle('A13')->getFont()->setBold(true);

        $sheet->setCellValue('A14', 'Rincian Menu');
        $sheet->setCellValue('B14', '[[RINCIAN_MENU]]');

        $sheet->setCellValue('A15', 'Kegiatan');
        $sheet->setCellValue('B15', '[[KEGIATAN]]');

        $sheet->setCellValue('A16', 'Sumber Dana');
        $sheet->setCellValue('B16', '[[SUMBER_DANA]]');

        $sheet->setCellValue('A17', 'Total Anggaran');
        $sheet->setCellValue('B17', '[[TOTAL]]');
        $sheet->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');

        $sheet->setCellValue('A18', 'Jumlah Item');
        $sheet->setCellValue('B18', '[[ITEM_COUNT]]');

        // === TABEL ITEM ===
        $sheet->setCellValue('A20', 'No');
        $sheet->setCellValue('B20', 'Uraian Item');
        $sheet->setCellValue('C20', 'Volume');
        $sheet->setCellValue('D20', 'Satuan');
        $sheet->setCellValue('E20', 'Harga Satuan');
        $sheet->setCellValue('F20', 'Jumlah (Rp)');

        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2']
            ]
        ];

        $sheet->getStyle('A20:F20')->applyFromArray($headerStyle);

        // Items template row
        $sheet->setCellValue('A21', '[[ITEMS]]');

        // === RAB BLOCK END ===
        $sheet->setCellValue('A23', '[[RAB_BLOCK_END]]');

        // === TANDA TANGAN ===
        $sheet->setCellValue('F25', 'Menyetujui,');
        $sheet->setCellValue('F26', 'Kepala Puskesmas');
        $sheet->setCellValue('F35', '([[NAMA_KEPALA]])');
        $sheet->setCellValue('F36', 'NIP. [[NIP_KEPALA]]');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(20);

        // Row heights
        $sheet->getRowDimension(1)->setRowHeight(15);
        $sheet->getRowDimension(2)->setRowHeight(15);
        $sheet->getRowDimension(3)->setRowHeight(18);
        $sheet->getRowDimension(6)->setRowHeight(20);
        $sheet->getRowDimension(20)->setRowHeight(25);

        return $spreadsheet;
    }
}