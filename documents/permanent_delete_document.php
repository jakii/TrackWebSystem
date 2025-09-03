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

$stmt = $db->prepare("DELETE FROM documents WHERE id = ? AND uploaded_by = ?");
$result = $stmt->execute([$doc_id, $user_id]);

if ($result) {
    // Log activity
    $doc_title = 'Unknown';
    // Try to get the title before deletion
    if (!empty($doc_id)) {
        $stmt = $db->prepare("SELECT title FROM documents WHERE id = ?");
        $stmt->execute([$doc_id]);
        $doc = $stmt->fetch();
        if ($doc && isset($doc['title'])) {
            $doc_title = $doc['title'];
        }
    }
    logActivity($db, $user_id, "Permanently deleted document: {$doc_title} (ID: {$doc_id})");
    header('Location: trash.php?success=' . urlencode('Document permanently deleted'));
} else {
    header('Location: trash.php?error=' . urlencode('Failed to delete document'));
}
exit;
