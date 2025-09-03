<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';

requireAuth();

$current_folder_id = $_GET['folder'] ?? null;
$current_folder = null;
$view_mode = $_GET['view'] ?? null;

if ($current_folder_id) {
    $result = $db->query("SELECT * FROM folders WHERE id = " . (int)$current_folder_id);
    $current_folder = $result->fetch();

    if (!$current_folder) {
        header('Location: browse.php');
        exit();
    }
}

// check if user can access this folder
if ($current_folder && !isAdmin() && isAdmin()) {
    $result = $db->query(
        "SELECT COUNT(*) as count FROM documents d
         LEFT JOIN document_shares ds ON d.id = ds.document_id AND ds.shared_with = " . (int)$_SESSION['user_id'] . "
         WHERE d.folder_id = " . (int)$current_folder_id . " AND 
         (d.uploaded_by = " . (int)$_SESSION['user_id'] . " OR d.is_public = 1 OR ds.id IS NOT NULL)"
    );
    if ($result->fetch()['count'] == 0) {
        header('Location: browse.php?error=access_denied');
        exit();
    }
}

// get breadcrumb
$breadcrumb = [];
if ($current_folder_id) {
    $breadcrumb = getFolderPath($db, $current_folder_id);
}

// get subfolders
$subfolders = [];
$query = "
    SELECT f.*, COUNT(DISTINCT d.id) as document_count,
           (SELECT COUNT(*) FROM folders WHERE parent_id = f.id) as subfolder_count
    FROM folders f 
    LEFT JOIN documents d ON f.id = d.folder_id AND (d.is_deleted IS NULL OR d.is_deleted = 0)
    WHERE f.parent_id " . ($current_folder_id ? "= " . (int)$current_folder_id : "IS NULL") . "
    GROUP BY f.id 
    ORDER BY f.sort_order, f.name
";
$subfolders = $db->query($query)->fetchAll();

// get documents
$documents = [];
if (isAdmin()) {
    $query = "
        SELECT d.*, u.full_name as uploader_name,
               CASE WHEN ds.id IS NOT NULL THEN 1 ELSE 0 END as is_shared_with_me
        FROM documents d 
        JOIN users u ON d.uploaded_by = u.id 
        LEFT JOIN document_shares ds ON d.id = ds.document_id AND ds.shared_with = " . (int)$_SESSION['user_id'] . "
        WHERE d.folder_id " . ($current_folder_id ? "= " . (int)$current_folder_id : "IS NULL") . "
        AND (d.is_deleted IS NULL OR d.is_deleted = 0)
        AND (d.uploaded_by = " . (int)$_SESSION['user_id'] . " OR d.is_public = 1 OR ds.id IS NOT NULL)
        ORDER BY d.created_at DESC
    ";
    $documents = $db->query($query)->fetchAll();
} else {
    $query = "
        SELECT d.*, u.full_name as uploader_name,
               CASE WHEN ds.id IS NOT NULL THEN 1 ELSE 0 END as is_shared_with_me,
               ds.permission as share_permission
        FROM documents d 
        JOIN users u ON d.uploaded_by = u.id 
        LEFT JOIN document_shares ds ON d.id = ds.document_id AND ds.shared_with = " . (int)$_SESSION['user_id'] . "
        WHERE d.folder_id " . ($current_folder_id ? "= " . (int)$current_folder_id : "IS NULL") . "
    AND (d.uploaded_by = " . (int)$_SESSION['user_id'] . " OR d.is_public = 1 OR ds.id IS NOT NULL)
    AND (d.is_deleted IS NULL OR d.is_deleted = 0)
        ORDER BY d.created_at DESC
    ";
    $documents = $db->query($query)->fetchAll();
}

$page_title = $current_folder ? $current_folder['name'] . ' - Browse' : 'Document Browser';

// Renders folder sidebar tree
function renderSidebarTree($db, $parent_id = null, $level = 0, $current_id = null) {
    $user_id = $_SESSION['user_id'];
    $is_admin = isAdmin();

    $query = "
        SELECT f.*, COUNT(DISTINCT d.id) as document_count
        FROM folders f 
        LEFT JOIN documents d ON f.id = d.folder_id 
        WHERE f.parent_id " . ($parent_id ? "= " . (int)$parent_id : "IS NULL") . "
        GROUP BY f.id 
        ORDER BY f.sort_order, f.name
    ";

    $folders = $db->query($query)->fetchAll();

    foreach ($folders as $folder) {
        $is_current = $folder['id'] == $current_id;
        echo '<div class="sidebar-folder-item" style="padding-left: ' . ($level * 15) . 'px;">';
        echo '<a href="browse.php?folder=' . $folder['id'] . '" class="sidebar-folder-link ' . ($is_current ? 'active' : '') . '">';
        echo '<i class="fas fa-folder me-1" style="color: ' . htmlspecialchars($folder['color']) . ';"></i>';
        echo htmlspecialchars($folder['name']);
        if ($folder['document_count'] > 0) {
            echo ' <small class="text-muted">(' . $folder['document_count'] . ')</small>';
        }
        echo '</a>';
        echo '</div>';

        renderSidebarTree($db, $folder['id'], $level + 1, $current_id);
    }
}

// Folder creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_folder') {
    $folder_name = trim($_POST['folder_name'] ?? '');
    $folder_description = trim($_POST['folder_description'] ?? '');
    $folder_color = $_POST['folder_color'] ?? '#007bff';

    if ($folder_name) {
        $sort_order_query = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM folders WHERE parent_id " . ($current_folder_id ? "= ?" : "IS NULL"));
        if ($current_folder_id) {
            $sort_order_query->execute([$current_folder_id]);
        } else {
            $sort_order_query->execute();
        }
        $next_order = $sort_order_query->fetch()['next_order'];

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

        header('Location: browse.php' . ($current_folder_id ? '?folder=' . $current_folder_id : ''));
        exit();
    }
}
?>
