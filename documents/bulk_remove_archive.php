<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_docs'])) {
    $selected_docs = $_POST['selected_docs'];
    $user_id = $_SESSION['user_id'];
    $is_admin = isAdmin();

    if (empty($selected_docs)) {
        header('Location: archive_document.php?error=No documents selected.');
        exit();
    }

    // Build placeholders for query
    $placeholders = implode(',', array_fill(0, count($selected_docs), '?'));

    if ($is_admin) {
        // Admins can unarchive any document
        $sql = "UPDATE documents 
                SET is_archived = 0, archived_at = NULL 
                WHERE id IN ($placeholders) AND is_archived = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute($selected_docs);
    } else {
        // Regular users can only unarchive their own
        $sql = "UPDATE documents 
                SET is_archived = 0, archived_at = NULL 
                WHERE id IN ($placeholders) AND uploaded_by = ? AND is_archived = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([...$selected_docs, $user_id]);
    }

    // Log activity
    foreach ($selected_docs as $doc_id) {
        logActivity($db, $user_id, "Unarchived document: ID $doc_id");
    }

    header('Location: archive_document.php?success=Documents unarchived successfully.');
    exit();
} else {
    header('Location: archive_document.php?error=Invalid request.');
    exit();
}
