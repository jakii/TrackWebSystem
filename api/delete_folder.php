<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder_id'])) {
    $folder_id = (int)$_POST['folder_id'];

    // Check if folder exists
    $folder_check = $db->prepare("SELECT id FROM folders WHERE id = ?");
    $folder_check->execute([$folder_id]);
    $folder = $folder_check->fetch();

    if (!$folder) {
        header("Location: browse.php?error=folder_not_found");
        exit;
    }

    // 1️⃣ Check if folder has documents
    $doc_check = $db->prepare("SELECT COUNT(*) as total FROM documents WHERE folder_id = ? AND (is_deleted IS NULL OR is_deleted = 0)");
    $doc_check->execute([$folder_id]);
    $doc_count = $doc_check->fetch()['total'];

    if ($doc_count > 0) {
        header("Location: browse.php?folder=$folder_id&error=has_documents");
        exit;
    }

    // 2️⃣ Check if folder has subfolders
    $sub_check = $db->prepare("SELECT COUNT(*) as total FROM folders WHERE parent_id = ?");
    $sub_check->execute([$folder_id]);
    $sub_count = $sub_check->fetch()['total'];

    if ($sub_count > 0) {
        header("Location: browse.php?folder=$folder_id&error=has_subfolders");
        exit;
    }

    // 3️⃣ Safe to delete
    $delete = $db->prepare("DELETE FROM folders WHERE id = ?");
    $delete->execute([$folder_id]);

    header("Location: browse.php?success=folder_deleted");
    exit;
}
