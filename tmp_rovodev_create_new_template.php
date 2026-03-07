<?php
// Script untuk membuat template baru sesuai format yang diberikan

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

try {
    echo "Creating new template with your format...\n";
    
    // Create new document
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    
    // Add title
    $section->addText('SURAT TIBA BERANGKAT', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
    $section->addText('Nomor: ${no_surat}', ['bold' => true], ['alignment' => 'center']);
    $section->addTextBreak(2);
    
    // Template untuk 2 desa sesuai format Anda
    $section->addText('Berangkat Dari	:	Desa ${desa_1}');
    $section->addText('Tiba di	:	Desa ${desa_1}	Ke	:	Desa ${desa_2}');
    $section->addText('Pada Tanggal	:	${tanggal_1}	Pada Tanggal	:	${tanggal_1}');
    $section->addText('Kepala 	:	Desa ${desa_1}');
    $section->addTextBreak(3);
    $section->addText('${kepala_desa_1}	Kepala	:	Desa ${desa_1}');
    $section->addTextBreak(3);
    $section->addText('${kepala_desa_1}');
    $section->addTextBreak(3);
    
    $section->addText('Berangkat Dari	:	Desa ${desa_2}');
    $section->addText('Tiba di	:	Desa ${desa_2}	Ke	:	Desa ${desa_1}');
    $section->addText('Pada Tanggal	:	${tanggal_2}	Pada Tanggal	:	${tanggal_selesai}');
    $section->addText('Kepala 	:	Desa ${desa_2}');
    $section->addTextBreak(3);
    $section->addText('${kepala_desa_2}	Kepala	:	Desa ${desa_2}');
    $section->addTextBreak(3);
    $section->addText('${kepala_desa_2}');
    
    // Save template
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save('storage/app/templates/2desa_new_format.docx');
    
    echo "✅ New template created: storage/app/templates/2desa_new_format.docx\n";
    echo "✅ Template menggunakan format placeholder yang Anda berikan\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>