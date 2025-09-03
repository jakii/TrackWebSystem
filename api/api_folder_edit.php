<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folder_id = (int)$_POST['folder_id'];
    $folder_name = trim($_POST['folder_name']);
    $folder_description = trim($_POST['folder_description']);
    $folder_color = $_POST['folder_color'];

    if (!empty($folder_name) && $folder_id > 0) {
        $stmt = $db->prepare("UPDATE folders SET name = ?, description = ?, color = ? WHERE id = ?");
        $stmt->execute([$folder_name, $folder_description, $folder_color, $folder_id]);

        $parent_stmt = $db->prepare("SELECT parent_id FROM folders WHERE id = ?");
        $parent_stmt->execute([$folder_id]);
        $parent = $parent_stmt->fetch();
        $parent_id = $parent ? $parent['parent_id'] : 0;

        header("Location: ../folders/folder_view.php?folder=$parent_id");
        exit();
    }
}

header("Location: ../folders/folder_view.php");
exit();
