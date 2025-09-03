<?php
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

header('Content-Type: application/json');
requireAuth();

$user_id  = $_SESSION['user_id'] ?? null;
$is_admin = isAdmin();

try {
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
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $documents = [];
    }

    echo json_encode(['status' => 'success', 'documents' => $documents]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
