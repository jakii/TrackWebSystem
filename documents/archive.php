<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$document_id = (int)($_GET['id'] ?? 0);

if (!$document_id) {
    header('Location: ../dashboard.php?error=Invalid document ID.');
    exit();
}

// Check ownership or admin privileges
$stmt = $db->prepare("SELECT * FROM documents WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$document_id, $_SESSION['user_id']]);
$document = $stmt->fetch();

if (!$document && !isAdmin()) {
    header('Location: ../dashboard.php?error=Document not found or access denied.');
    exit();
}

if (isAdmin() && !$document) {
    $stmt = $db->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->execute([$document_id]);
    $document = $stmt->fetch();
}

if (!$document) {
    header('Location: ../dashboard.php?error=Document not found.');
    exit();
}

// Archive the document
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_archive'])) {
    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $stmt = $db->prepare("UPDATE documents SET is_archived = 1, archived_at = NOW() WHERE id = ?");
        if ($stmt->execute([$document_id])) {
            // Log activity
            $user_id = $_SESSION['user_id'];
            $doc_title = $document['title'] ?? 'Unknown';
            logActivity($db, $user_id, "Archived document: {$doc_title} (ID: {$document_id})");
            
            $redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? '../dashboard.php';
            header("Location: $redirect?success=Document archived successfully.");
            exit();
        } else {
            $error = 'Failed to archive document.';
        }
    } else {
        $error = 'Invalid security token.';
    }
}

require_once '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-box-archive me-2"></i>Archive Document
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> The document will be archived but not deleted. You can restore it later if needed.
                </div>
                
                <p>Are you sure you want to archive the following document?</p>
                
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="<?php echo getFileIcon(pathinfo($document['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                            <?php echo htmlspecialchars($document['title']); ?>
                        </h6>
                        <p class="card-text">
                            <small class="text-muted">
                                File: <?php echo htmlspecialchars($document['original_filename']); ?><br>
                                Size: <?php echo formatFileSize($document['file_size']); ?><br>
                                Uploaded: <?php echo date('F j, Y g:i A', strtotime($document['created_at'])); ?>
                            </small>
                        </p>
                    </div>
                </div>

                <form method="POST" class="mt-3">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect'] ?? $_POST['redirect'] ?? '../dashboard.php'); ?>">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo htmlspecialchars($_GET['redirect'] ?? $_POST['redirect'] ?? '../dashboard.php'); ?>" 
                           class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" name="confirm_archive" class="btn btn-warning text-dark">
                            <i class="fas fa-box-archive me-2"></i>Archive Document
                        </button>
                    </div>
                </form>               
            </div>
        </div>
    </div>
</div>
