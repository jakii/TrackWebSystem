<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAuth();

$user_id  = $_SESSION['user_id'] ?? null;
$is_admin = isAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_docs'])) {
    $selected_docs = $_POST['selected_docs'];

    try {
        foreach ($selected_docs as $doc_id) {
            $stmt = $db->prepare("SELECT uploaded_by FROM documents WHERE id = ? AND is_deleted = 1");
            $stmt->execute([$doc_id]);
            $doc = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$doc) continue;

            if (!$is_admin && $doc['uploaded_by'] != $user_id) {
                continue;
            }

            $update = $db->prepare("UPDATE documents SET is_deleted = 0, deleted_at = NULL WHERE id = ?");
            $update->execute([$doc_id]);
        }

        header("Location: trash.php?status=restored");
        exit;
    } catch (PDOException $e) {
        die("Error restoring documents: " . $e->getMessage());
    }
} else {
    header("Location: trash.php");
    exit;
}
