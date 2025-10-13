<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/activity_logger.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder_id'])) {
    $folder_id = (int)$_POST['folder_id'];

    // check kung valid folder
    $folder_check = $db->prepare("SELECT * FROM folders WHERE id = ?");
    $folder_check->execute([$folder_id]);
    $folder = $folder_check->fetch();

    if (!$folder) {
        header("Location: ../documents/browse.php?error=notfound");
        exit();
    }

    // check kung may documents
    $doc_check = $db->prepare("SELECT COUNT(*) as cnt FROM documents WHERE folder_id = ? AND (is_deleted IS NULL OR is_deleted = 0)");
    $doc_check->execute([$folder_id]);
    $doc_count = $doc_check->fetch()['cnt'];

    // check kung may subfolders
    $sub_check = $db->prepare("SELECT COUNT(*) as cnt FROM folders WHERE parent_id = ?");
    $sub_check->execute([$folder_id]);
    $sub_count = $sub_check->fetch()['cnt'];

    if ($doc_count > 0 || $sub_count > 0) {
        // may laman -> bawal i-delete
        header("Location: ../documents/browse.php?folder=$folder_id&error=notempty");
        exit();
    }

    // wala laman -> pwede i-delete
    $delete_folder = $db->prepare("DELETE FROM folders WHERE id = ?");
    $delete_folder->execute([$folder_id]);

    logActivity($db, $_SESSION['user_id'], "Deleted folder: " . $folder['name']);

    if ($folder['parent_id']) {
        $redirect = "../documents/browse.php?folder=" . $folder['parent_id'] . "&success=folder_deleted";
    } else {
        $redirect = "../documents/browse.php?success=folder_deleted";
    }
    header("Location: $redirect");
    exit();
    } else {
    header("Location: ../documents/browse.php?error=invalid");
    exit();
}
