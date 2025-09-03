<?php
require_once 'includes/auth_check.php';
require_once 'config/database.php';

requireAuth();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not authenticated.');
}

$is_admin = isAdmin(); // para sa conditional queries

// 📊 Document Statistics
if ($is_admin) {
    $total_documents = $db->query("SELECT COUNT(*) as total FROM documents")->fetch()['total'] ?? 0;
    $total_size = $db->query("SELECT SUM(file_size) as total_size FROM documents")->fetch()['total_size'] ?? 0;
} else {
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM documents WHERE uploaded_by = ?");
    $stmt->execute([$user_id]);
    $total_documents = $stmt->fetch()['total'] ?? 0;

    $stmt = $db->prepare("SELECT SUM(file_size) as total_size FROM documents WHERE uploaded_by = ?");
    $stmt->execute([$user_id]);
    $total_size = $stmt->fetch()['total_size'] ?? 0;
}

// 📈 Weekly Uploads
if ($is_admin) {
    $weekly_uploads_stmt = $db->prepare("
      SELECT DATE(created_at) AS upload_date, COUNT(*) AS count
      FROM documents
      WHERE created_at >= CURDATE() - INTERVAL 6 DAY
      GROUP BY upload_date
      ORDER BY upload_date
    ");
    $weekly_uploads_stmt->execute();
} else {
    $weekly_uploads_stmt = $db->prepare("
      SELECT DATE(created_at) AS upload_date, COUNT(*) AS count
      FROM documents
      WHERE uploaded_by = ?
        AND created_at >= CURDATE() - INTERVAL 6 DAY
      GROUP BY upload_date
      ORDER BY upload_date
    ");
    $weekly_uploads_stmt->execute([$user_id]);
}

$weekly_uploads_raw = $weekly_uploads_stmt->fetchAll();

// Fill missing days
$weekly_uploads = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $weekly_uploads[$date] = 0;
}
foreach ($weekly_uploads_raw as $row) {
    $weekly_uploads[$row['upload_date']] = (int)$row['count'];
}

// 📄 Pagination
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Total documents for pagination
if ($is_admin) {
    $totalDocumentsStmt = $db->query("SELECT COUNT(*) FROM documents WHERE (is_deleted IS NULL OR is_deleted = 0)");
    $totalDocuments = (int)$totalDocumentsStmt->fetchColumn();
} else {
    $totalDocumentsStmt = $db->prepare("
        SELECT COUNT(*) 
        FROM documents 
        WHERE uploaded_by = ? AND (is_deleted IS NULL OR is_deleted = 0)
    ");
    $totalDocumentsStmt->execute([$user_id]);
    $totalDocuments = (int)$totalDocumentsStmt->fetchColumn();
}

$totalPages = ceil($totalDocuments / $perPage);

// Fetch paginated documents
// Fetch documents
if ($is_admin) {
    // Admin → still paginated
    $recent_documents_stmt = $db->prepare("
        SELECT d.*, 
           c.name AS category_name, 
           c.color AS category_color,
           f.name AS folder_name,
           f.color AS folder_color
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        WHERE (d.is_deleted IS NULL OR d.is_deleted = 0)
        ORDER BY d.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $recent_documents_stmt->bindValue(1, $perPage, PDO::PARAM_INT);
    $recent_documents_stmt->bindValue(2, $offset, PDO::PARAM_INT);
} else {
    // USER → show all documents (no pagination)
    $recent_documents_stmt = $db->prepare("
        SELECT d.*, 
           c.name AS category_name, 
           c.color AS category_color,
           f.name AS folder_name,
           f.color AS folder_color
        FROM documents d
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        WHERE d.uploaded_by = ?
          AND (d.is_deleted IS NULL OR d.is_deleted = 0)
        ORDER BY d.created_at DESC
    ");
    $recent_documents_stmt->execute([$user_id]);
}


$recent_documents_stmt->execute();
$recent_documents = $recent_documents_stmt->fetchAll();

// 📂 5 Documents Shared With User
$shared_documents = $db->prepare("
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
    LIMIT 5
");
$shared_documents->execute([$user_id]);
$shared_documents = $shared_documents->fetchAll();

// 📋 Get All Categories
$categories = $db->prepare("SELECT * FROM categories ORDER BY name");
$categories->execute();
$categories = $categories->fetchAll();
?>