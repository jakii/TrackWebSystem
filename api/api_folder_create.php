<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Move document to folder
    if (isset($_POST['move_doc_id']) && isset($_POST['target_folder_id'])) {
        $doc_id = (int)$_POST['move_doc_id'];
        $target_folder_id = (int)$_POST['target_folder_id'];
        // Only allow move if user owns the document or is admin
        $stmt = $db->prepare("SELECT * FROM documents WHERE id = ? AND (uploaded_by = ? OR ?)");
        $is_admin = isAdmin();
        $stmt->execute([$doc_id, $_SESSION['user_id'], $is_admin]);
        $doc = $stmt->fetch();
        if ($doc) {
            $update = $db->prepare("UPDATE documents SET folder_id = ? WHERE id = ?");
            $update->execute([$target_folder_id, $doc_id]);
            header("Location: ../folders/folder_view.php?folder=". $target_folder_id . "&success=Document moved successfully");
            exit();
        } else {
            header("Location: ../folders/folder_view.php?error=Access denied");
            exit();
        }
    }
    // Create folder
    $folder_name = trim($_POST['folder_name'] ?? '');
    $folder_description = trim($_POST['folder_description'] ?? '');
    $folder_color = $_POST['folder_color'] ?? '';
    $parent_folder_id = isset($_POST['parent_folder_id']) ? (int)$_POST['parent_folder_id'] : null;
    $parent_id = $parent_folder_id > 0 ? $parent_folder_id : null;
    if (!empty($folder_name)) {
        $created_by = $_SESSION['user_id'];
        $stmt = $db->prepare("INSERT INTO folders (name, description, color, parent_id, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$folder_name, $folder_description, $folder_color, $parent_id, $created_by]);
        header("Location: ../folders/folder_view.php?folder=". ($parent_id ?? 0));
        exit();
    }
}
header("Location: ../folders/folder_view.php");
exit();