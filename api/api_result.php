<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$search = trim($_GET['search'] ?? '');
$user_id = $_SESSION['user_id'] ?? null;

if ($search && $user_id) {
    logActivity($db, $user_id, "Searched for: '{$search}'");
}

$category_id = !empty($_GET['category']) ? (int)$_GET['category'] : null;
$file_type   = trim($_GET['file_type'] ?? '');
$dateFilter  = $_GET['date'] ?? '';
$sort_by     = $_GET['sort'] ?? 'created_at';
$sort_order  = ($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$page        = max(1, (int)($_GET['page'] ?? 1));

$where_conditions = [];
$params = [];
$where_conditions[] = '(d.is_deleted IS NULL OR d.is_deleted = 0)';
$where_conditions[] = "(d.uploaded_by = ? OR d.is_public = 1)";
$params[] = $user_id;

if ($search) {
    $where_conditions[] = "(d.title LIKE ? OR d.description LIKE ? OR d.tags LIKE ? OR d.original_filename LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if ($category_id) {
    $where_conditions[] = "d.category_id = ?";
    $params[] = $category_id;
}

if ($file_type) {
    $where_conditions[] = "d.file_type LIKE ?";
    $params[] = "%$file_type%";
}

if (!empty($dateFilter)) {
    $where_conditions[] = "DATE(d.created_at) = ?";
    $params[] = $dateFilter;
}

$where_clause = implode(' AND ', $where_conditions);
$valid_sorts = ['title', 'created_at', 'file_size', 'download_count'];
if (!in_array($sort_by, $valid_sorts)) $sort_by = 'created_at';

# ----------------------------
# Folder query
# ----------------------------
$folder_sql = "
    SELECT 
        f.id,
        f.name AS title,
        f.description,
        NULL AS tags,
        NULL AS file_type,
        f.created_at,
        NULL AS file_size,
        NULL AS download_count,
        'folder' AS type,
        NULL AS category_name,
        f.color AS category_color,
        u.full_name AS uploader_name,
        f.created_by AS uploaded_by
    FROM folders f
    LEFT JOIN users u ON f.created_by = u.id
    WHERE 1 = 1
";
$folder_params = [];

//Include search filter para sa lahat ng users
if ($search) {
    $folder_sql .= " AND (f.name LIKE ? OR f.description LIKE ?)";
    $folder_params[] = "%$search%";
    $folder_params[] = "%$search%";
}

if (!empty($dateFilter)) {
    $folder_sql .= " AND DATE(f.created_at) = ?";
    $folder_params[] = $dateFilter;
}

# ----------------------------
# Document query
# ----------------------------
$document_sql = "
    SELECT 
        d.id,
        d.title,
        d.description,
        d.tags,
        d.file_type,
        d.created_at,
        d.file_size,
        d.download_count,
        'document' AS type,
        c.name AS category_name,
        c.color AS category_color,
        u.full_name AS uploader_name,
        d.uploaded_by
    FROM documents d
    LEFT JOIN categories c ON d.category_id = c.id
    LEFT JOIN users u ON d.uploaded_by = u.id
    WHERE $where_clause
";

# ----------------------------
# Combine both queries
# ----------------------------
$combined_sql = "
    ($document_sql)
    UNION ALL
    ($folder_sql)
    ORDER BY created_at $sort_order
    LIMIT " . ITEMS_PER_PAGE . " OFFSET " . (($page - 1) * ITEMS_PER_PAGE);

$combined_params = array_merge($params, $folder_params);

$query = $db->prepare($combined_sql);
$query->execute($combined_params);
$results = $query->fetchAll();

# ----------------------------
# Count total results
# ----------------------------
$count_sql = "
    SELECT SUM(total) AS total FROM (
        SELECT COUNT(*) AS total FROM documents d WHERE $where_clause
        UNION ALL
        SELECT COUNT(*) AS total FROM folders f WHERE f.created_by = ?
        " . ($search ? "AND (f.name LIKE ? OR f.description LIKE ?)" : "") . "
    ) AS combined
";

$count_params = array_merge($params, [$user_id]);
if ($search) {
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
}

$count_query = $db->prepare($count_sql);
$count_query->execute($count_params);
$total_results = $count_query->fetch()['total'] ?? 0;

$total_pages = ceil($total_results / ITEMS_PER_PAGE);

?>
