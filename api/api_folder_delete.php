<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folder_id = (int)$_POST['folder_id'];

    $stmt = $db->prepare("SELECT parent_id FROM folders WHERE id = ?");
    $stmt->execute([$folder_id]);
    $folder = $stmt->fetch();
    $parent_id = $folder ? $folder['parent_id'] : 0;

    $docCheck = $db->prepare("SELECT COUNT(*) FROM documents WHERE folder_id = ? AND (is_deleted IS NULL OR is_deleted = 0)");
    $docCheck->execute([$folder_id]);
    $docCount = $docCheck->fetchColumn();

    if ($docCount > 0) {
        header("Location: ../documents/browse.php?folder=$parent_id&error=has_documents");
        exit();
    }

    $delete_subfolders = $db->prepare("DELETE FROM folders WHERE parent_id = ?");
    $delete_subfolders->execute([$folder_id]);

    $db->prepare("DELETE FROM folders WHERE id = ?")->execute([$folder_id]);

    header("Location: ../documents/browse.php?folder=$parent_id&success=folder_deleted");
    exit();
    }

    header("Location: ../documents/browse.php");
    exit();
