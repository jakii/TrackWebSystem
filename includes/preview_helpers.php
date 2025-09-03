<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\Settings as WordSettings;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as ExcelPdfWriter;
use PhpOffice\PhpWord\PhpWord;

WordSettings::setPdfRendererName('DomPDF');
WordSettings::setPdfRendererPath(__DIR__ . '/../vendor/dompdf/dompdf');

function embedPdf($pdfFile) {
    return '<iframe src="data:application/pdf;base64,' 
         . base64_encode(file_get_contents($pdfFile)) 
         . '" style="width:100%;height:600px;border:none;"></iframe>';
}

function previewDocx($filePath) {
    if (!file_exists($filePath)) return 'File not found.';
    try {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
        $pdfFile = sys_get_temp_dir() . '/' . uniqid('docx_', true) . '.pdf';
        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfFile);
        return embedPdf($pdfFile);
    } catch (Exception $e) {
        return "Unable to convert DOCX to PDF: " . $e->getMessage();
    }
}

function previewXlsx($filePath) {
    if (!file_exists($filePath)) return 'File not found.';
    try {
        $spreadsheet = ExcelIOFactory::load($filePath);
        $pdfFile = sys_get_temp_dir() . '/' . uniqid('xlsx_', true) . '.pdf';
        $writer = new ExcelPdfWriter($spreadsheet);
        $writer->save($pdfFile);
        return embedPdf($pdfFile);
    } catch (Exception $e) {
        return "Unable to convert XLSX to PDF: " . $e->getMessage();
    }
}

function previewPptx($filePath) {
    if (!file_exists($filePath)) return 'File not found.';

    $zip = new ZipArchive;
    if ($zip->open($filePath) === true) {
        // wrapper na may consistent alignment at spacing
        $output = '<div class="pptx-preview text-start">'; 
        $slideCount = 1;

        while (true) {
            $slideXml = $zip->getFromName("ppt/slides/slide{$slideCount}.xml");
            if ($slideXml === false) break;

            $xml = simplexml_load_string($slideXml);
            $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

            $texts = $xml->xpath('//a:t');
            $slideText = implode(' ', array_map('strval', $texts));

            // bawat slide naka-consistent style
            $output .= '<div class="mb-3 p-3 border rounded bg-light shadow-sm">';
            $output .= '<p class="fw-bold mb-2">Slide ' . $slideCount . ':</p>';
            $output .= '<div class="text-start">' . nl2br(htmlspecialchars($slideText)) . '</div>';
            $output .= '</div>';

            $slideCount++;
        }

        $output .= '</div>'; // close pptx-preview
        $zip->close();
        return $output ?: 'No content found in slides.';
    }

    return 'Unable to open PowerPoint file.';
}

function previewZip($filePath) {
    if (!file_exists($filePath)) return 'File not found.';

    $zip = new ZipArchive;
    if ($zip->open($filePath) === true) {
        $html = '<ul class="list-group mb-3">';
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $fileName = htmlspecialchars($stat['name']);
            $fileSize = number_format($stat['size'] / 1024, 2) . ' KB';

            $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
            $html .= '<span><i class="bi bi-file-earmark"></i> ' . $fileName . '</span>';
            $html .= '<span class="badge bg-secondary rounded-pill">' . $fileSize . '</span>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        $zip->close();
        return $html;
    }

    return 'Unable to open ZIP file.';
}

?>
