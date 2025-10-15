<?php
// api/api_archive.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

if ($is_admin) {
    $stmt = $db->query("
        SELECT d.*, c.name AS category_name, u.full_name AS owner_name
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN users u ON d.uploaded_by = u.id
        WHERE d.is_archived = 1
        ORDER BY d.archived_at DESC
    ");
} else {
    $stmt = $db->prepare("
        SELECT d.*, c.name AS category_name
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        WHERE d.uploaded_by = ? AND d.is_archived = 1
        ORDER BY d.archived_at DESC
    ");
    $stmt->execute([$user_id]);
}

$archived_documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
