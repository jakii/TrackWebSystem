<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
requireAuth();

$user_id  = $_SESSION['user_id'] ?? null;
$is_admin = isAdmin();

// === PERMANENT DELETE ALL ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {

    if ($is_admin) {
        // Admin: lahat ng trash documents
        $stmt = $db->query("SELECT file_path FROM documents WHERE is_deleted = 1");
        $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($docs as $doc) {
            if (!empty($doc['file_path'])) {
                $file_path = __DIR__ . '/../uploads/' . basename($doc['file_path']);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

        // Delete records sa database
        $db->exec("DELETE FROM documents WHERE is_deleted = 1");

    } else {
        // Regular user: sarili lang niyang trash documents
        $stmt = $db->prepare("SELECT file_path FROM documents WHERE is_deleted = 1 AND uploaded_by = ?");
        $stmt->execute([$user_id]);
        $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($docs as $doc) {
            if (!empty($doc['file_path'])) {
                $file_path = __DIR__ . '/../uploads/' . basename($doc['file_path']);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

        $delete_stmt = $db->prepare("DELETE FROM documents WHERE is_deleted = 1 AND uploaded_by = ?");
        $delete_stmt->execute([$user_id]);
    }

    // Redirect after delete
    header("Location: trash.php?deleted_all=1");
    exit;
}

// === FETCH TRASH DOCUMENTS ===
if ($is_admin) {
    $stmt = $db->query("
        SELECT d.*, 
               c.name AS category_name, 
               f.name AS folder_name,
               u.full_name AS owner_name
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        LEFT JOIN users u ON d.uploaded_by = u.id
        WHERE d.is_deleted = 1
        ORDER BY d.deleted_at DESC
    ");
} else {
    $stmt = $db->prepare("
        SELECT d.*, 
               c.name AS category_name, 
               f.name AS folder_name,
               u.full_name AS owner_name
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        LEFT JOIN users u ON d.uploaded_by = u.id
        WHERE d.is_deleted = 1 AND d.uploaded_by = ?
        ORDER BY d.deleted_at DESC
    ");
    $stmt->execute([$user_id]);
}

$trash_documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>