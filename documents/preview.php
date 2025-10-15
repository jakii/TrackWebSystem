<?php
require_once '../includes/header.php';
include '../api/api_preview.php';
require_once '../includes/preview_helpers.php';
?>
<div>
    <div>
        <div class="card shadow rounded-4 border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <!-- Go Back Button -->
                    <a href="javascript:void(0);" 
                       onclick="if (document.referrer !== '') { window.history.back(); } else { window.location.href='../dashboard.php'; }" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Go Back
                    </a>

                    <h5 class="mb-0">
                        <i class="<?php echo getFileIcon($file_extension); ?> me-2"></i>
                        <?php echo htmlspecialchars($document['title']); ?>
                    </h5>
                </div>

            <div>
               <a href="download.php?id=<?php echo $document['id']; ?>" class="btn" style="border:none;">
                   <i class="fas fa-download me-2"></i>
               </a>
               <a href="view.php?id=<?php echo $document['id']; ?>" class="btn" style="border:none;">
                   <i class="fas fa-info-circle me-2"></i>
               </a>
               <?php if ($document['uploaded_by'] == $_SESSION['user_id']): ?>
                   <a href="share.php?id=<?php echo $document['id']; ?>" class="btn" style="border:none;">
                       <i class="fas fa-share me-2"></i>
                   </a>
               <?php endif; ?>

    <!-- Print Button -->
    <?php if (!$is_pdf): ?>
    <button id="printBtn" 
            class="btn" 
            style="border:none;">
        <i class="fas fa-print me-2"></i>
    </button>
    <?php endif; ?>
</div>
<script>
document.getElementById('printBtn').addEventListener('click', function() {
    const cardBody = document.querySelector('.card-body');
    const clone = cardBody.cloneNode(true);

    // Remove scroll restrictions
    clone.style.maxHeight = 'none';
    clone.style.overflow = 'visible';

    // Automatically insert page breaks for tall elements
    const pageHeight = 1100; // approx pixels for A4, adjust as needed
    let accumulatedHeight = 0;

    const elements = clone.querySelectorAll('div, table, img, pre, .bg-light');
    elements.forEach(el => {
        const elHeight = el.offsetHeight;

        if (accumulatedHeight + elHeight > pageHeight) {
            // Insert page break before this element
            const breakDiv = document.createElement('div');
            breakDiv.style.pageBreakBefore = 'always';
            el.parentNode.insertBefore(breakDiv, el);
            accumulatedHeight = elHeight;
        } else {
            accumulatedHeight += elHeight;
        }
    });

    // Open print window
    const printWindow = window.open('', '', 'width=900,height=650');

    printWindow.document.write(`
        <html>
        <head>
            <title>Print Preview</title>
            <style>
                body { font-family: 'Times New Roman', serif; font-size:14px; line-height:1.6; padding:20px; }

                /* Prevent images from splitting across pages */
                img { max-width:100%; height:auto; display:block; margin:10px auto; page-break-inside: avoid; }

                /* Tables */
                table { border-collapse: collapse; width: 100%; margin: 15px 0; page-break-inside: auto; }
                table td, table th { border: 1px solid #000; padding: 6px 10px; }
                tr { page-break-inside: avoid; page-break-after: auto; }

                /* Preformatted text */
                pre { white-space: pre-wrap; word-wrap: break-word; }

                /* Scrollable divs */
                .bg-light { background-color: #f8f9fa !important; padding:15px; border-radius:5px; max-height: none !important; overflow: visible !important; }

                /* Avoid breaking inside headings and paragraphs */
                h1, h2, h3, h4, h5, h6, p { page-break-inside: avoid; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${clone.outerHTML}
        </body>
        </html>
    `);

    printWindow.document.close();
});
</script>
            </div>
            <div class="card-body">
                <?php if ($is_image): ?>
                    <!-- Image Preview -->
                    <div class="text-center">
                        <?php
                        if (file_exists($document['file_path']) && is_readable($document['file_path'])) {
                            $imageUrl = $document['public_url'];
                            echo '<img src="' . htmlspecialchars($imageUrl) . '" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height: 600px;"
                                 alt="' . htmlspecialchars($document['title']) . '"
                                 onerror="this.style.display=\'none\'; document.getElementById(\'image-fallback\').style.display=\'block\';">
                                 
                                 <div id="image-fallback" class="alert alert-warning" style="display: none;">
                                     <i class="fas fa-exclamation-triangle me-2"></i>
                                     Could not load image. <a href="download.php?id=' . $document['id'] . '">Download instead</a>.
                                 </div>';
                        } else {
                            echo '<div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Image file not found or not readable. <a href="download.php?id=' . $document['id'] . '">Download file</a>.
                            </div>';
                        }
                        ?>
                    </div>

                <?php elseif ($is_pdf): ?>
                    <!-- PDF Preview -->
                    <?= previewPdf($document['file_path'], "download.php?id=" . $document['id']); ?>

                <?php elseif ($is_text): ?>
                    <!-- Text File Preview -->
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;"><?php 
                            $content = file_get_contents($document['file_path']);
                            echo htmlspecialchars(mb_substr($content, 0, 5000)); 
                            if (strlen($content) > 5000) echo "\n\n... (File truncated for preview)";
                        ?></pre>    
                    </div>

                <?php elseif ($is_word): ?>
                    <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                        <?= previewDocx($document['file_path']) ?>
                    </div>

                <?php elseif ($is_excel): ?>
                    <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                        <?= previewXlsx($document['file_path']) ?>
                    </div>

                <?php elseif ($is_powerpoint): ?>
                    <div class="bg-light p-3 rounded" style="max-height: 600px; overflow-y: auto;">
                         <?php
                         echo previewPptx($document['file_path'], $document['public_url']);
                         ?>
                    </div>

                <?php else: ?>
                    <!-- Unsupported File Type -->
                    <div class="text-center py-5">
                        <i class="<?php echo getFileIcon($file_extension); ?> fa-5x text-muted mb-3"></i>
                        <h5 class="text-muted">Preview not available</h5>
                        <p class="text-muted">
                            This file type cannot be previewed in the browser.<br>
                            File type: <?php echo strtoupper($file_extension); ?> 
                            (<?php echo htmlspecialchars($document['file_type']); ?>)
                        </p>
                        <a href="download.php?id=<?php echo $document['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>Download to View
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>