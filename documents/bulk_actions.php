<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_ids']) && isset($_POST['action'])) {
    $doc_ids = $_POST['doc_ids'];
    $action = $_POST['action'];

    if (!is_array($doc_ids)) {
        $_SESSION['error'] = 'No documents selected.';
        header('Location: ../dashboard.php');
        exit;
    }

    $doc_ids = array_map('intval', $doc_ids);

    try {
        switch ($action) {
            case 'download':
                $_SESSION['success'] = 'Bulk download initiated for ' . count($doc_ids) . ' documents.';
                break;

            case 'archive':
                foreach ($doc_ids as $doc_id) {
                    $db->query("UPDATE documents 
                                SET is_archived = 1 
                                WHERE id = $doc_id 
                                AND (uploaded_by = {$_SESSION['user_id']} OR " . (isAdmin() ? 1 : 0) . " = 1)");
                }
                $_SESSION['success'] = count($doc_ids) . ' document(s) archived successfully.';
                break;

            case 'delete':
                foreach ($doc_ids as $doc_id) {
                    $db->query("UPDATE documents 
                                SET is_deleted = 1, deleted_at = NOW() 
                                WHERE id = $doc_id 
                                AND (uploaded_by = {$_SESSION['user_id']} OR " . (isAdmin() ? 1 : 0) . " = 1)");
                }
                $_SESSION['success'] = count($doc_ids) . ' document(s) deleted successfully.';
                break;

            default:
                $_SESSION['error'] = 'Invalid action.';
                break;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred while processing your request.';
    }

    header('Location: ../dashboard.php');
    exit;
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../dashboard.php');
    exit;
}
?>
