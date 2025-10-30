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

    //Safe to delete
    $delete = $db->prepare("DELETE FROM folders WHERE id = ?");
    $delete->execute([$folder_id]);

    header("Location: browse.php?success=folder_deleted");
    exit;
}
