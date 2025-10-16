<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';

requireAuth();

$current_folder_id = isset($_GET['folder']) ? (int)$_GET['folder'] : null;
$category_query = $db->prepare("SELECT * FROM categories ORDER BY name");
$category_query->execute();
$categories = $category_query->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (!isset($_FILES['documents']) || empty($_FILES['documents']['name'][0])) {
        $error = "Please select at least one file.";
    } else {
        foreach ($_FILES['documents']['name'] as $i => $name) {
            $tmp  = $_FILES['documents']['tmp_name'][$i];
            $size = $_FILES['documents']['size'][$i];
            $type = $_FILES['documents']['type'][$i];
            $err  = $_FILES['documents']['error'][$i];

            if ($err !== UPLOAD_ERR_OK) {
                continue;
            }

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, ALLOWED_EXTENSIONS)) {
                continue;
            }
            if ($size > MAX_FILE_SIZE) {
                continue;
            }

            // Generate unique filename for internal storage
            $unique = uniqid() . "_" . time() . "." . $ext;
            $dest   = UPLOAD_DIR . $unique;

            if (move_uploaded_file($tmp, $dest)) {
                // Create backup with original filename
                
                
                $title = pathinfo($name, PATHINFO_FILENAME);
                $folder_id   = !empty($_POST['folder_id']) ? (int)$_POST['folder_id'] : null;
                $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
                $description = $_POST['description'] ?? '';
                $tags        = $_POST['tags'] ?? '';
                $is_public   = isset($_POST['is_public']) ? 1 : 0;

                // Fixed INSERT query without backup_path column
                $stmt = $db->prepare("
                    INSERT INTO documents 
                    (title, description, filename, original_filename, file_size, file_type, file_path, folder_id, category_id, uploaded_by, is_public, tags) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $title,
                    $description,
                    $unique,
                    $name,
                    $size,
                    $type,
                    $dest,
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
                    $dest
                ]);
                
                logActivity($db, $_SESSION['user_id'], "Document uploaded: $title");
            }
        }
    }
}
?>