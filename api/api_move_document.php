<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
require_once '../includes/activity_logger.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'move_document') {
    $document_id = (int)$_POST['document_id'];
    $target_folder_id = !empty($_POST['target_folder_id']) ? (int)$_POST['target_folder_id'] : null;
    
    // Validate document ownership/access
    $document_check = $db->prepare("
        SELECT d.*, u.full_name as uploader_name 
        FROM documents d 
        JOIN users u ON d.uploaded_by = u.id 
        WHERE d.id = ? AND (d.uploaded_by = ? OR d.is_public = 1)
    ");
    $document_check->execute([$document_id, $_SESSION['user_id']]);
    $document = $document_check->fetch();
    
    if (!$document) {
        $_SESSION['error'] = "Document not found or you don't have permission to move it.";
        header('Location: ../documents/browse.php');
        exit();
    }
    
    // If target folder is specified, validate it exists
    if ($target_folder_id) {
        $folder_check = $db->prepare("SELECT id FROM folders WHERE id = ?");
        $folder_check->execute([$target_folder_id]);
        if (!$folder_check->fetch()) {
            $_SESSION['error'] = "Target folder not found.";
            header('Location: ../documents/browse.php');
            exit();
        }
    }
    
    // Update document folder
    $update_query = $db->prepare("UPDATE documents SET folder_id = ? WHERE id = ?");
    $update_query->execute([$target_folder_id, $document_id]);
    
    // Log activity
    $current_folder_id = $_POST['current_folder_id'] ?? null;
    $target_folder_name = $target_folder_id ? getFolderName($db, $target_folder_id) : 'Root';
    
    logActivity($db, $_SESSION['user_id'], "Document moved: {$document['title']} to {$target_folder_name}");
    
    $_SESSION['success'] = "Document moved successfully to {$target_folder_name}";
    
    $redirect = $current_folder_id ? "../documents/browse.php?folder=$current_folder_id" : "../documents/browse.php";
    header("Location: $redirect");
    exit();
}

// Helper function to get folder name
function getFolderName($db, $folder_id) {
    $query = $db->prepare("SELECT name FROM folders WHERE id = ?");
    $query->execute([$folder_id]);
    $folder = $query->fetch();
    return $folder ? $folder['name'] : 'Unknown Folder';
}
?>