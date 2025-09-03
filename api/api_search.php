<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

//get search
// Log search activity
$search = trim($_GET['search'] ?? '');
$user_id = $_SESSION['user_id'] ?? null;
if ($search && $user_id) {
    logActivity($db, $user_id, "Searched for: '{$search}'");
}
$category_id = !empty($_GET['category']) ? (int)$_GET['category'] : null;
$file_type = trim($_GET['file_type'] ?? '');
$sort_by = $_GET['sort'] ?? 'created_at';
$sort_order = ($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$page = max(1, (int)($_GET['page'] ?? 1));

//build query

$where_conditions = [];
$params = [];

// Exclude deleted documents
$where_conditions[] = '(d.is_deleted IS NULL OR d.is_deleted = 0)';

//user documents or public documents
$where_conditions[] = "(d.uploaded_by = ? OR d.is_public = 1)";
$params[] = $_SESSION['user_id'];

//search condition
if ($search) {
    $where_conditions[] = "(d.title LIKE ? OR d.description LIKE ? OR d.tags LIKE ? OR d.original_filename LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

//category filter
if ($category_id) {
    $where_conditions[] = "d.category_id = ?";
    $params[] = $category_id;
}

//file type filter
if ($file_type) {
    $where_conditions[] = "d.file_type LIKE ?";
    $params[] = "%$file_type%";
}

$where_clause = implode(' AND ', $where_conditions);

//sort_by column
$valid_sorts = ['title', 'created_at', 'file_size', 'download_count'];
if (!in_array($sort_by, $valid_sorts)) {
    $sort_by = 'created_at';
}

//count total results
$count_sql = "SELECT COUNT(*) as total FROM documents d WHERE $where_clause";
$count_query = $db->prepare($count_sql);
$count_query->execute($params);
$total_results = $count_query->fetch()['total'] ?? 0;

//calculate pagination
$total_pages = ceil($total_results / ITEMS_PER_PAGE);
$offset = ($page - 1) * ITEMS_PER_PAGE;

//matching documents

$document_sql = "
    SELECT d.*, c.name as category_name, c.color as category_color, u.full_name as uploader_name
    FROM documents d 
    LEFT JOIN categories c ON d.category_id = c.id 
    LEFT JOIN users u ON d.uploaded_by = u.id
    WHERE $where_clause
    ORDER BY d.$sort_by $sort_order
    LIMIT " . ITEMS_PER_PAGE . " OFFSET $offset
";

$document_query = $db->prepare($document_sql);
$document_query->execute($params);
$documents = $document_query->fetchAll();

//get all categories
$category_query = $db->prepare("SELECT * FROM categories ORDER BY name");
$category_query->execute();
$categories = $category_query->fetchAll();
?>