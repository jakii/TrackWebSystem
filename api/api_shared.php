<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $db->prepare("
        SELECT d.*, 
               c.name AS category_name, 
               c.color AS category_color, 
               f.name AS folder_name,
               f.color AS folder_color,
               u.full_name AS owner_name, 
               ds.permission
        FROM documents d 
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        LEFT JOIN users u ON d.uploaded_by = u.id
        JOIN document_shares ds ON d.id = ds.document_id
        WHERE ds.shared_with = ? 
        ORDER BY ds.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $shared_documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (basename($_SERVER['SCRIPT_FILENAME']) === 'api_shared.php') {
        header('Content-Type: application/json');
        echo json_encode($shared_documents);
        exit;
    }

    return $shared_documents;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    exit;
}
