<?php
require_once __DIR__ . '/../vendor/autoload.php';

function previewDocx($filePath) {
    if (!file_exists($filePath)) {
        return '<div class="alert alert-warning">File not found.</div>';
    }

    try {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
        $html = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {

                // --- Paragraphs ---
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $paraStyle = $element->getParagraphStyle();
                    $align = $paraStyle ? $paraStyle->getAlignment() : 'left';
                    $alignCss = "text-align:{$align};";

                    $paraText = '';
                    foreach ($element->getElements() as $child) {
                        if (method_exists($child, 'getText')) {
                            $paraText .= htmlspecialchars($child->getText());
                        }
                    }
                    $html .= "<p style='$alignCss'>$paraText</p>";
                }

                // --- Tables ---
                if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    $html .= '<table class="docx-table">';
                    foreach ($element->getRows() as $row) {
                        $html .= '<tr>';
                        foreach ($row->getCells() as $cell) {
                            $cellHtml = '';
                            foreach ($cell->getElements() as $cellElement) {
                                if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    $paraStyle = $cellElement->getParagraphStyle();
                                    $align = $paraStyle ? $paraStyle->getAlignment() : 'left';
                                    $alignCss = "text-align:{$align};";

                                    $cellText = '';
                                    foreach ($cellElement->getElements() as $child) {
                                        if (method_exists($child, 'getText')) {
                                            $cellText .= htmlspecialchars($child->getText());
                                        }
                                    }
                                    $cellHtml .= "<div style='$alignCss'>$cellText</div>";
                                }
                            }
                            $html .= '<td>' . $cellHtml . '</td>';
                        }
                        $html .= '</tr>';
                    }
                    $html .= '</table><br>';
                }
            }
        }

        // --- Images ---
        $zip = new ZipArchive;
        $imgHtml = '';
        if ($zip->open($filePath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if (preg_match('/^word\/media\/.+\.(jpg|jpeg|png|gif)$/i', $entryName)) {
                    $imageData = $zip->getFromIndex($i);
                    $base64 = base64_encode($imageData);
                    $mime = mime_content_type('data://application/octet-stream;base64,' . $base64);
                    $imgHtml .= '<img src="data:' . $mime . ';base64,' . $base64 . '" 
                                    class="img-fluid mb-3" 
                                    style="max-width:100%; height:auto; display:block; margin:10px auto;">';
                }
            }
            $zip->close();
        }

        // --- CSS Design + Fix Alignment ---
        $customCss = "
            <style>
                .docx-preview {
                    font-family: 'Times New Roman', serif;
                    line-height: 1.6;
                    font-size: 14px;
                    max-width: 900px;
                    margin: 20px auto;
                    padding: 40px;
                    background: #fff;
                    border: 1px solid #ddd;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                }
                .docx-preview p {
                    margin: 0 0 12px 0;
                    writing-mode: horizontal-tb !important;
                    white-space: normal !important;
                }
                .docx-table {
                    border-collapse: collapse;
                    margin: 15px 0;
                    width: 100%;
                }
                .docx-table td, 
                .docx-table th {
                    border: 1px solid #ccc;
                    padding: 6px 10px;
                    vertical-align: top;
                    writing-mode: horizontal-tb !important;
                    white-space: normal !important;
                }
                .docx-preview img {
                    max-width: 100%;
                    height: auto;
                }
            </style>
        ";

        return $customCss . "
            <div class='docx-preview'>
                $html
                <div>$imgHtml</div>
            </div>";

    } catch (Exception $e) {
        return '<div class="alert alert-warning">Unable to preview DOCX: ' 
             . htmlspecialchars($e->getMessage()) . '</div>';
    }
}


function previewXlsx($filePath) {
    if (!file_exists($filePath)) return '<div class="alert alert-warning">File not found.</div>';

    $html = '<table class="table table-bordered table-sm">';
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $rowLimit = 20;
        foreach (array_slice($rows, 0, $rowLimit) as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        if (count($rows) > $rowLimit) {
            $html .= '<tr><td colspan="100%">... (Truncated - showing first ' . $rowLimit . ' rows)</td></tr>';
        }

    } catch (Exception $e) {
        return '<div class="alert alert-warning">Unable to preview XLSX: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    return $html . '</table>';
}

// FIXED: Added second parameter for URL
function previewPptx($filePath, $publicUrl = null) {
    if (!file_exists($filePath)) return '<div class="alert alert-warning">File not found.</div>';
    
    // For better UX, provide download link and show available options
    $output = '<div class="alert alert-info">';
    $output .= '<strong>PowerPoint Preview:</strong><br>';
    $output .= 'For full presentation experience, please download the file.<br>';
    
    if ($publicUrl) {
        $output .= '<a href="' . htmlspecialchars($publicUrl) . '" class="btn btn-primary mt-2" download>Download Presentation</a>';
    }
    $output .= '</div>';
    
    // Try to extract text content as fallback
    $zip = new ZipArchive;
    if ($zip->open($filePath) === true) {
        $textOutput = '';
        $slideCount = 1;
        while ($slideCount <= 50) { // Safety limit
            $slideXml = $zip->getFromName("ppt/slides/slide" . $slideCount . ".xml");
            if ($slideXml === false) break;
            
            $xml = simplexml_load_string($slideXml);
            if ($xml) {
                $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
                $texts = $xml->xpath('//a:t');
                $slideText = implode(' ', array_map('strval', $texts));
                if (!empty(trim($slideText))) {
                    $textOutput .= "<strong>Slide $slideCount:</strong><br>" . 
                                  htmlspecialchars($slideText) . "<br><br>";
                }
            }
            $slideCount++;
        }
        $zip->close();
        
        if (!empty($textOutput)) {
            $output .= '<div class="mt-3"><h6>Presentation Content:</h6>' . $textOutput . '</div>';
        }
    }
    
    return $output ?: '<div class="alert alert-warning">No preview available for this PowerPoint file.</div>';
}

function filePathToUrl($filePath) {
    // Security check - ensure file exists and is readable
    if (!file_exists($filePath) || !is_readable($filePath)) {
        return false;
    }
    
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];
    
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $fileRealPath = realpath($filePath);
    
    // Ensure the file is within the document root for security
    if (strpos($fileRealPath, $docRoot) !== 0) {
        return false;
    }
    
    $relativePath = str_replace($docRoot, '', $fileRealPath);
    $relativePath = str_replace('\\', '/', ltrim($relativePath, '/\\'));
    
    return $protocol . '://' . $host . '/' . $relativePath;
}

function previewPdf($filePath, $downloadUrl = '') {
    if (!file_exists($filePath)) return '<div class="alert alert-warning">File not found.</div>';
    
    $url = filePathToUrl($filePath);
    if (!$url) {
        return '<div class="alert alert-warning">Cannot generate secure preview URL. <a href="' . 
               htmlspecialchars($downloadUrl) . '">Download instead</a>.</div>';
    }

    return '<div class="embed-responsive" style="height: 600px;">
                <iframe src="' . htmlspecialchars($url) . '" 
                        class="w-100 h-100 border-0 rounded"
                        title="PDF Preview"></iframe>
            </div>';
}

function previewText($filePath) {
    if (!file_exists($filePath)) return '<div class="alert alert-warning">File not found.</div>';

    $content = file_get_contents($filePath);    
    $truncated = false;
    if (strlen($content) > 5000) {
        $content = mb_substr($content, 0, 5000);
        $truncated = true;
    }
    return '<div class="bg-light p-3 rounded">
                <pre class="mb-0" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;">' . 
                htmlspecialchars($content) . 
                ($truncated ? "\n\n... (File truncated for preview)" : '') . 
                '</pre> 
            </div>';
}
?>