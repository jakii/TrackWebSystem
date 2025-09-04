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

//get document details
$doc_query = $db->prepare("
    SELECT d.*, c.name as category_name, c.color as category_color, u.full_name as uploader_name
    FROM documents d
    LEFT JOIN categories c ON d.category_id = c.id
    LEFT JOIN users u ON d.uploaded_by = u.id
    WHERE d.id = ? AND (d.uploaded_by = ? OR d.is_public = 1)
");
$doc_query->execute([$document_id, $_SESSION['user_id']]);
$document = $doc_query->fetch();

if (!$document) {
    header('Location: ../dashboard.php?error=Document not found or access denied.');
    exit();
}
logDocumentActivity($document_id, $_SESSION['user_id'], 'view');
//check if user can delete this document
$can_delete = ($document['uploaded_by'] == $_SESSION['user_id'] || isAdmin());

//handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && $can_delete) {
    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        //delete file
        if (file_exists($document['file_path'])) {
            unlink($document['file_path']);
        }

        //delete document
        $delete_doc_query = $db->prepare("DELETE FROM documents WHERE id = ?");
        if ($delete_doc_query->execute([$document_id])) {
            header('Location: ../dashboard.php?success=Document deleted successfully.');
            exit();
        } else {
            $error = 'Failed to delete document.';
        }
    } else {
        $error = 'Invalid security token.';
    }
}
?>
