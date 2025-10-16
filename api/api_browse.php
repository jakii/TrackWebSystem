<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
require_once '../includes/activity_logger.php';
require_once '../includes/storage_functions.php';

requireAuth();

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_folder') {
    $folder_name = trim($_POST['folder_name'] ?? '');
    $folder_description = trim($_POST['folder_description'] ?? '');
    $folder_color = $_POST['folder_color'] ?? '#007bff';

    // kunin muna ang current folder id mula sa POST
    $current_folder_id = !empty($_POST['current_folder_id']) ? (int)$_POST['current_folder_id'] : null;
    $current_folder = null;

    if ($current_folder_id) {
        $folder_fetch = $db->prepare("SELECT * FROM folders WHERE id = ?");
        $folder_fetch->execute([$current_folder_id]);
        $current_folder = $folder_fetch->fetch();
        if (!$current_folder) {
            $current_folder_id = null; // invalid parent folder
        }
    }

    if ($folder_name) {
        // calculate next sort order
        $sort_order_query = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM folders WHERE parent_id " . ($current_folder_id ? "= ?" : "IS NULL"));
        if ($current_folder_id) {
            $sort_order_query->execute([$current_folder_id]);
        } else {
            $sort_order_query->execute();
        }
        $next_order = $sort_order_query->fetch()['next_order'];

        // calculate folder level
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


$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $current_folder_id = !empty($_POST['current_folder_id']) ? (int)$_POST['current_folder_id'] : null;

    // ✅ Check if folder exists
    if ($current_folder_id) {
        $folder_check = $db->prepare("SELECT id FROM folders WHERE id = ?");
        $folder_check->execute([$current_folder_id]);
        if (!$folder_check->fetch()) {
            $current_folder_id = null;
        }
    }

    // ✅ Shared storage check (GLOBAL STORAGE LIMIT)
    $total_used = getTotalStorageUsed($db);
    $limit = getStorageLimit($db);

    // Compute total upload size for this batch
    $batch_total = array_sum($_FILES['documents']['size']);

    if (($total_used + $batch_total) > $limit) {
        echo "<script>alert('❌ Upload failed: Storage limit reached. Please delete old files or contact admin.'); window.history.back();</script>";
        exit();
    }

    foreach ($_FILES['documents']['name'] as $i => $name) {
        $tmp_file  = $_FILES['documents']['tmp_name'][$i];
        $file_size = $_FILES['documents']['size'][$i];
        $file_type = $_FILES['documents']['type'][$i];
        $file_error  = $_FILES['documents']['error'][$i];

        if ($file_error !== UPLOAD_ERR_OK) continue;

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS)) continue;
        if ($file_size > MAX_FILE_SIZE) continue;

        $total_used = getTotalStorageUsed($db);
        if (($total_used + $file_size) > $limit) {
            echo "<script>alert('❌ Upload blocked: Not enough remaining storage.'); window.history.back();</script>";
            exit();
        }

        $unique_filename = uniqid() . "_" . time() . "." . $ext;
        $destination    = UPLOAD_DIR . $unique_filename;
        
        if (move_uploaded_file($tmp_file, $destination)) {
            $title       = pathinfo($name, PATHINFO_FILENAME);
            $folder_id   = $current_folder_id;
            $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $description = $_POST['description'] ?? '';
            $tags        = $_POST['tags'] ?? '';
            $is_public   = isset($_POST['is_public']) ? 1 : 0;

            $insert_document = $db->prepare("
                INSERT INTO documents 
                (title, description, filename, original_filename, file_size, file_type, file_path, folder_id, category_id, uploaded_by, is_public, tags) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert_document->execute([
                $title,
                $description,
                $unique_filename,
                $name,
                $file_size,
                $file_type,
                $destination,
                $folder_id,
                $category_id,
                $_SESSION['user_id'],
                $is_public,
                $tags
            ]);

            $insert_report = $db->prepare("
                INSERT INTO reports (title, uploaded_by, file_path, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $insert_report->execute([
                $title,
                $_SESSION['user_id'],
                $destination
            ]);

            logActivity($db, $_SESSION['user_id'], "Document uploaded: $title");
        }
    }

    $redirect = $current_folder_id ? "../documents/browse.php?folder=$current_folder_id" : "../documents/browse.php";
    echo "<script>window.location.href='$redirect&success=uploaded';</script>";
    exit();
}

$current_folder_id = $_GET['folder'] ?? null;
$current_folder = null;

if ($current_folder_id) {
    $folder_fetch = $db->prepare("SELECT * FROM folders WHERE id = ?");
    $folder_fetch->execute([$current_folder_id]);
    $current_folder = $folder_fetch->fetch();

    if (!$current_folder) {
        header('Location: browse.php');
        exit();
    }
}

if ($current_folder && !isAdmin()) {
    $access_check = $db->prepare("
        SELECT COUNT(*) as count 
        FROM documents d
        LEFT JOIN document_shares ds 
            ON d.id = ds.document_id AND ds.shared_with = ?
        WHERE d.folder_id = ? 
          AND (d.uploaded_by = ? OR d.is_public = 1 OR ds.id IS NOT NULL)
    ");
    $access_check->execute([$_SESSION['user_id'], $current_folder_id, $_SESSION['user_id']]);
    $docCount = $access_check->fetch()['count'];

    $subfolder_check = $db->prepare("SELECT COUNT(*) as subfolders_count FROM folders WHERE parent_id = ?");
    $subfolder_check->execute([$current_folder_id]);
    $subfolders_count = $subfolder_check->fetch()['subfolders_count'];
}

$breadcrumb = $current_folder_id ? getFolderPath($db, $current_folder_id) : [];

$subfolders = $db->query("
    SELECT f.*, COUNT(DISTINCT d.id) as document_count,
           (SELECT COUNT(*) FROM folders WHERE parent_id = f.id) as subfolder_count
    FROM folders f 
    LEFT JOIN documents d ON f.id = d.folder_id AND (d.is_deleted IS NULL OR d.is_deleted = 0)
    WHERE f.parent_id " . ($current_folder_id ? "= " . (int)$current_folder_id : "IS NULL") . "
    GROUP BY f.id 
    ORDER BY f.sort_order, f.name
")->fetchAll();

$documentsQuery = "
    SELECT d.*, u.full_name as uploader_name,
           CASE WHEN ds.id IS NOT NULL THEN 1 ELSE 0 END as is_shared_with_me,
           ds.permission as share_permission
    FROM documents d 
    JOIN users u ON d.uploaded_by = u.id 
    LEFT JOIN document_shares ds 
        ON d.id = ds.document_id AND ds.shared_with = " . (int)$_SESSION['user_id'] . "
    WHERE d.folder_id " . ($current_folder_id ? "= " . (int)$current_folder_id : "IS NULL") . "
      AND (d.uploaded_by = " . (int)$_SESSION['user_id'] . " OR d.is_public = 1 OR ds.id IS NOT NULL)
      AND (d.is_deleted IS NULL OR d.is_deleted = 0)
    ORDER BY d.created_at DESC
";

$documents = $db->query($documentsQuery)->fetchAll();

$page_title = $current_folder ? $current_folder['name'] . ' - Browse' : 'Document Browser';
?>
