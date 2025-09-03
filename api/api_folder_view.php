<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';

requireAuth();
requireAdmin();

//get current folder
$current_folder_id = $_GET['folder'] ?? null;
$current_folder = null;

if ($current_folder_id) {
    $folder_query = $db->prepare("SELECT * FROM folders WHERE id = ?");
    $folder_query->execute([$current_folder_id]);
    $current_folder = $folder_query->fetch();

    // Only allow access if user is owner or admin
    if (!$current_folder || ($current_folder['created_by'] != $_SESSION['user_id'] && !isAdmin())) {
        header('Location: folder_view.php?error=Access denied');
        exit();
    }
}

//get breadcrumb
$breadcrumb = [];
if ($current_folder_id) {
    $breadcrumb = getFolderPath($db, $current_folder_id);
}

//get subfolders
// Only show subfolders created by the user or if admin
$subfolder_query = $db->prepare("
    SELECT f.*, COUNT(d.id) as document_count,
           (SELECT COUNT(*) FROM folders WHERE parent_id = f.id) as subfolder_count
    FROM folders f
    LEFT JOIN documents d ON f.id = d.folder_id
    WHERE f.parent_id " . ($current_folder_id ? "= ?" : "IS NULL") . " AND (f.created_by = ? OR ?)
    GROUP BY f.id 
    ORDER BY f.name
");

$is_admin = isAdmin();
if ($current_folder_id) {
    $subfolder_query->execute([$current_folder_id, $_SESSION['user_id'], $is_admin]);
} else {
    $subfolder_query->execute([$_SESSION['user_id'], $is_admin]);
}
$subfolders = $subfolder_query->fetchAll();

//get documents
$document_query = $db->prepare("
    SELECT d.*, u.full_name as uploader_name 
    FROM documents d 
    JOIN users u ON d.uploaded_by = u.id 
    WHERE d.folder_id " . ($current_folder_id ? "= ?" : "IS NULL") . "
    AND (d.is_deleted IS NULL OR d.is_deleted = 0) ORDER BY d.created_at DESC
");

if ($current_folder_id) {
    $document_query->execute([$current_folder_id]);
} else {
    $document_query->execute();
}
$documents = $document_query->fetchAll();

//handle folder creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_folder') {
    $folder_name = trim($_POST['folder_name'] ?? '');
    $folder_description = trim($_POST['folder_description'] ?? '');
    $folder_color = $_POST['folder_color'] ?? '#007bff';

    if ($folder_name) {
        //calculate next sort order
        $sort_order_query = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM folders WHERE parent_id " . ($current_folder_id ? "= ?" : "IS NULL"));
        if ($current_folder_id) {
            $sort_order_query->execute([$current_folder_id]);
        } else {
            $sort_order_query->execute();
        }
        $next_order = $sort_order_query->fetch()['next_order'];

        //calculate
        $level = $current_folder ? $current_folder['level'] + 1 : 0;

        $insert_folder_query = $db->prepare("
            INSERT INTO folders (name, description, color, parent_id, level, sort_order, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insert_folder_query->execute([
            $folder_name,
            $folder_description,
            $folder_color,
            $current_folder_id,
            $level,
            $next_order,
            $_SESSION['user_id']
        ]);

        header('Location: folder_view.php' . ($current_folder_id ? '?folder=' . $current_folder_id : ''));
        exit();
    }
}

$page_title = $current_folder ? $current_folder['name'] . ' - Folder View' : 'Root Folders';

// Get recent folders for the user
$recent_folders_query = $db->prepare("SELECT * FROM folders WHERE created_by = ? ORDER BY id DESC LIMIT 5");
$recent_folders_query->execute([$_SESSION['user_id']]);
$recent_folders = $recent_folders_query->fetchAll();
?>
