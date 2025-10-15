<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Check if user has selected documents and an action
if (empty($_POST['doc_ids']) || empty($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'No documents or action specified.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false;
$doc_ids = $_POST['doc_ids'];
$action = $_POST['action'];

if (!is_array($doc_ids)) {
    $doc_ids = explode(',', $doc_ids);
}

try {
    $db->beginTransaction();

    switch ($action) {
        case 'delete':
            // Only delete if the user owns the document or is admin
            $stmt = $db->prepare("
                DELETE FROM documents 
                WHERE id IN (" . implode(',', array_fill(0, count($doc_ids), '?')) . ")
                AND (uploaded_by = ? OR ? = 1)
            ");
            $stmt->execute([...$doc_ids, $user_id, (int)$is_admin]);
            $message = "Selected documents deleted successfully.";
            break;

        case 'archive':
            $stmt = $db->prepare("
                UPDATE documents 
                SET status = 'archived' 
                WHERE id IN (" . implode(',', array_fill(0, count($doc_ids), '?')) . ")
                AND (uploaded_by = ? OR ? = 1)
            ");
            $stmt->execute([...$doc_ids, $user_id, (int)$is_admin]);
            $message = "Selected documents archived successfully.";
            break;

        case 'restore':
            $stmt = $db->prepare("
                UPDATE documents 
                SET status = 'active' 
                WHERE id IN (" . implode(',', array_fill(0, count($doc_ids), '?')) . ")
                AND (uploaded_by = ? OR ? = 1)
            ");
            $stmt->execute([...$doc_ids, $user_id, (int)$is_admin]);
            $message = "Selected documents restored successfully.";
            break;

        default:
            throw new Exception("Unknown action: $action");
    }

    $db->commit();
    echo json_encode(['status' => 'success', 'message' => $message]);

} catch (Exception $e) {
    $db->rollBack();
    error_log("Bulk action error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while performing bulk action.']);
}
