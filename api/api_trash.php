<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
requireAuth();

$user_id  = $_SESSION['user_id'] ?? null;
$is_admin = isAdmin();

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