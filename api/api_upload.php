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
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $folder_id = $current_folder_id ?: (!empty($_POST['folder_id']) ? (int)$_POST['folder_id'] : null);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $tags = trim($_POST['tags'] ?? '');
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    if (empty($title)) {
        $error = 'Document title is required.';
    } elseif (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a file to upload.';
    } else {
        $file = $_FILES['document'];
        $original_filename = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_type = $file['type'];

        if ($file_size > MAX_FILE_SIZE) {
            $error = 'File size exceeds the maximum allowed size of ' . formatFileSize(MAX_FILE_SIZE) . '.';
        } else {
            $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
            if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
                $error = 'File type not allowed. Allowed types: ' . implode(', ', ALLOWED_EXTENSIONS);
            } else {
                if (!file_exists(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0755, true);
                }

                $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $file_path = UPLOAD_DIR . $unique_filename;

                if (move_uploaded_file($file_tmp, $file_path)) {
                    $insert_doc_query = $db->prepare("
                        INSERT INTO documents 
                        (title, description, filename, original_filename, file_size, file_type, file_path, folder_id, category_id, uploaded_by, is_public, tags) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $inserted = $insert_doc_query->execute([
                        $title,
                        $description,
                        $unique_filename,
                        $original_filename,
                        $file_size,
                        $file_type,
                        $file_path,
                        $folder_id,
                        $category_id,
                        $_SESSION['user_id'],
                        $is_public,
                        $tags
                    ]);

                    if ($inserted) {
                        $insert_report = $db->prepare("
                            INSERT INTO reports (title, uploaded_by, file_path) 
                            VALUES (?, ?, ?)
                        ");
                        $insert_report->execute([
                            $title,
                            $_SESSION['user_id'],
                            $file_path
                        ]);

                        logActivity($db, $_SESSION['user_id'], 'Document uploaded: ' . $title);

                        $redirect_url = $current_folder_id
                            ? 'browse.php?folder=' . $current_folder_id
                            : '../dashboard.php';

                        echo "<script>window.location.href = '{$redirect_url}?success=Document uploaded successfully!';</script>";
                        exit;
                    } else {
                        unlink($file_path);
                        $error = 'Failed to save document information.';
                    }
                } else {
                    $error = 'Failed to upload file.';
                }
            }
        }
    }
}
?>
