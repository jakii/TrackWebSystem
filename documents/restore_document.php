<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$user_id = $_SESSION['user_id'] ?? null;
$doc_id = $_POST['id'] ?? null;

if (!$user_id || !$doc_id) {
    header('Location: trash.php?error=' . urlencode('Invalid request'));
    exit;
}

$stmt = $db->prepare("UPDATE documents SET is_deleted = 0, deleted_at = NULL WHERE id = ? AND uploaded_by = ?");
$result = $stmt->execute([$doc_id, $user_id]);

if ($result) {
    // Get document title for logging
    $stmt = $db->prepare("SELECT title FROM documents WHERE id = ?");
    $stmt->execute([$doc_id]);
    $doc = $stmt->fetch();
    $doc_title = $doc['title'] ?? 'Unknown';
    logActivity($db, $user_id, "Restored document: {$doc_title} (ID: {$doc_id})");
    header('Location: trash.php?success=' . urlencode('Document restored successfully'));
} else {
    header('Location: trash.php?error=' . urlencode('Failed to restore document'));
}
exit;
