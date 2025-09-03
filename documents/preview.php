<?php
require_once '../includes/header.php';
include '../api/api_preview.php';
require_once '../includes/preview_helpers.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow rounded-4 border-0 fade-in delay-1">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="<?php echo getFileIcon($file_extension); ?> me-2"></i>
                    <?php echo htmlspecialchars($document['title']); ?>
                </h4>
                <div>
                    <a href="download.php?id=<?php echo $document['id']; ?>" class="btn" style="background-color: #004F80; color: white;">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                    <a href="view.php?id=<?php echo $document['id']; ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-info-circle me-2"></i>Details
                    </a>
                </div>
            </div>
<div class="card-body">
    <?php if ($is_image): ?>
        <!-- Image Preview -->
        <div class="text-center">
            <img src="<?php echo $document['file_path']; ?>" 
                 class="img-fluid rounded shadow" 
                 style="max-height: 600px;"
                 alt="<?php echo htmlspecialchars($document['title']); ?>">
        </div>

    <?php elseif ($is_pdf): ?>
        <!-- PDF Preview -->
        <div class="embed-responsive" style="height: 600px;">
            <iframe src="<?php echo $document['file_path']; ?>" 
                    class="w-100 h-100 border-0 rounded"
                    title="PDF Preview"></iframe>
        </div>

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
        <!-- Word Preview (Converted to PDF) -->
        <div class="embed-responsive" style="height: 600px;">
            <?= previewDocx($document['file_path']) ?>
        </div>

    <?php elseif ($is_excel): ?>
        <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
            <?= previewXlsx($document['file_path']) ?>
        </div>

    <?php elseif ($is_powerpoint): ?>
        <div class="bg-light p-3 rounded" style="max-height: 600px; overflow-y: auto;">
            <?php
            $pptxUrl = null;
            if (isset($document['public_url']) && filter_var($document['public_url'], FILTER_VALIDATE_URL)) {
                $pptxUrl = $document['public_url'];
            } else {
                $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
                $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $document['file_path']);
                $pptxUrl = $baseUrl . $relativePath;
            }
            echo previewPptx($document['file_path'], $pptxUrl);
            ?>
        </div>

    <?php elseif ($is_zip): ?>
        <div class="bg-light p-3 rounded" style="max-height: 600px; overflow-y: auto;">
            <?= previewZip($document['file_path']) ?>
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
    
    <div class="col-md-4">
        <div class="card shadow rounded-4 border-0 fade-in delay-2">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-share-alt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="download.php?id=<?php echo $document['id']; ?>" class="btn" style="background-color: #004F80; color: white;">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                    <?php if ($document['uploaded_by'] == $_SESSION['user_id']): ?>
                    <a href="share.php?id=<?php echo $document['id']; ?>" class="btn btn-outline-success">
                        <i class="fas fa-share me-2"></i>Share Document
                    </a>
                    <?php endif; ?>
                    <a href="../dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
        </div>
    </div>
</div>