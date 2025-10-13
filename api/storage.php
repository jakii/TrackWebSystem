<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/storage_functions.php';
requireAuth();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

// SYSTEM STORAGE (everyone can see this)
$total_used = getTotalStorageUsed($db);
$limit = getStorageLimit($db);
$percent_total = ($limit > 0) ? ($total_used / $limit) * 100 : 0;

// USER STORAGE
$stmt = $db->prepare("
    SELECT COALESCE(SUM(file_size), 0) AS used
    FROM documents
    WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
");
$stmt->execute([$user_id]);
$user_used = $stmt->fetchColumn();
$percent_user = ($limit > 0) ? ($user_used / $limit) * 100 : 0;

// Admin-only extra details
$users = $files = $folders = [];
if ($is_admin) {
    $users = $db->query("
        SELECT u.full_name, COALESCE(SUM(d.file_size),0) AS used
        FROM users u
        LEFT JOIN documents d ON u.id = d.uploaded_by
        GROUP BY u.id
        ORDER BY used DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $files = $db->query("
        SELECT d.original_filename, d.file_size, d.uploaded_by, d.created_at, u.full_name as uploader_name
        FROM documents d
        LEFT JOIN users u ON d.uploaded_by = u.id
        WHERE d.is_deleted = 0 OR d.is_deleted IS NULL
        ORDER BY d.file_size DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    if ($db->query("SHOW TABLES LIKE 'folders'")->rowCount() > 0) {
        $folders = $db->query("
            SELECT f.name, COALESCE(SUM(d.file_size),0) AS used
            FROM folders f
            LEFT JOIN documents d ON f.id = d.folder_id
            GROUP BY f.id
            ORDER BY used DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}

// User files
$user_files_stmt = $db->prepare("
    SELECT original_filename, file_size, created_at
    FROM documents
    WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
    ORDER BY created_at DESC
");
$user_files_stmt->execute([$user_id]);
$user_files = $user_files_stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
echo json_encode([
    'is_admin' => $is_admin,
    'total_used' => $total_used,
    'limit' => $limit,
    'percent_total' => $percent_total,
    'user_used' => $user_used,
    'percent_user' => $percent_user,
    'users' => $users,
    'files' => $files,
    'folders' => $folders,
    'user_files' => $user_files
]);
