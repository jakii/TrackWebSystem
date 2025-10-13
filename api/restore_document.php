<?php
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

header('Content-Type: application/json');
requireAuth();

$user_id  = $_SESSION['user_id'] ?? null;
$is_admin = isAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$document_id = $_POST['document_id'] ?? null;

if (!$document_id) {
    echo json_encode(['status' => 'error', 'message' => 'Document ID required']);
    exit;
}

try {
    // Check document details
    $stmt = $db->prepare("SELECT uploaded_by FROM documents WHERE id = :id AND is_deleted = 1");
    $stmt->execute([':id' => $document_id]);
    $doc = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$doc) {
        echo json_encode(['status' => 'error', 'message' => 'Document not found or not deleted']);
        exit;
    }

    // Only admin OR the owner can restore
    if (!$is_admin && $doc['uploaded_by'] != $user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Not authorized to restore this document']);
        exit;
    }

    // Restore
    $restore = $db->prepare("
        UPDATE documents 
        SET is_deleted = 0, deleted_at = NULL 
        WHERE id = :id
    ");
    $restore->execute([':id' => $document_id]);

    echo json_encode(['status' => 'success', 'message' => 'Document restored successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
