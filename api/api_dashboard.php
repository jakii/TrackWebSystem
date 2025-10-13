<?php
require_once 'includes/auth_check.php';
require_once 'config/database.php';

requireAuth();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not authenticated.');
}

$is_admin = isAdmin();

// Input validation
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);

// Function to get weekly uploads data
function getWeeklyUploads($db, $user_id = null, $is_admin = false) {
    try {
        if ($is_admin) {
            $stmt = $db->prepare("SELECT DATE(created_at) AS upload_date, COUNT(*) AS count FROM documents WHERE created_at >= CURDATE() - INTERVAL 6 DAY GROUP BY upload_date ORDER BY upload_date");
            $stmt->execute();
        } else {
            $stmt = $db->prepare("SELECT DATE(created_at) AS upload_date, COUNT(*) AS count FROM documents WHERE uploaded_by = ? AND created_at >= CURDATE() - INTERVAL 6 DAY GROUP BY upload_date ORDER BY upload_date");
            $stmt->execute([$user_id]);
        }
        
        $raw_data = $stmt->fetchAll();
        
        // Fill missing days
        $weekly_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $weekly_data[$date] = 0;
        }
        
        foreach ($raw_data as $row) {
            $weekly_data[$row['upload_date']] = (int)$row['count'];
        }
        
        return $weekly_data;
    } catch (PDOException $e) {
        error_log("Weekly uploads query error: " . $e->getMessage());
        return [];
    }
}

// Function to get document statistics
function getDocumentStats($db, $user_id = null, $is_admin = false) {
    try {
        if ($is_admin) {
            $stmt = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(file_size), 0) as total_size FROM documents WHERE is_deleted = 0 OR is_deleted IS NULL");
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) as total, COALESCE(SUM(file_size), 0) as total_size FROM documents WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)");
            $stmt->execute([$user_id]);
        }
        
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Document stats query error: " . $e->getMessage());
        return ['total' => 0, 'total_size' => 0];
    }
}

// Function to get recent documents
function getRecentDocuments($db, $user_id = null, $is_admin = false, $limit = null) {
    try {
        if ($is_admin) {
            $sql = "
                SELECT d.*, 
                       c.name AS category_name, 
                       c.color AS category_color,
                       f.name AS folder_name,
                       f.color AS folder_color,
                       u.full_name AS uploader_name
                FROM documents d
                LEFT JOIN categories c ON d.category_id = c.id
                LEFT JOIN folders f ON d.folder_id = f.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                WHERE (d.is_deleted IS NULL OR d.is_deleted = 0)
                ORDER BY d.created_at DESC
            ";
            if ($limit) {
                $sql .= " LIMIT " . (int)$limit;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } else {
            $sql = "
                SELECT d.*, 
                       c.name AS category_name, 
                       c.color AS category_color,
                       f.name AS folder_name,
                       f.color AS folder_color,
                       u.full_name AS uploader_name
                FROM documents d
                LEFT JOIN categories c ON d.category_id = c.id
                LEFT JOIN folders f ON d.folder_id = f.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                WHERE d.uploaded_by = ? 
                  AND (d.is_deleted IS NULL OR d.is_deleted = 0)
                ORDER BY d.created_at DESC
            ";
            if ($limit) {
                $sql .= " LIMIT " . (int)$limit;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute([$user_id]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Recent documents query error: " . $e->getMessage());
        return [];
    }
}


// 📊 Document Statistics with combined query
$stats = getDocumentStats($db, $user_id, $is_admin);
$total_documents = $stats['total'] ?? 0;
$total_size = $stats['total_size'] ?? 0;

// 📈 Weekly Uploads
$weekly_uploads = getWeeklyUploads($db, $user_id, $is_admin);

// 📄 Recent Documents (limited to 15 for show more functionality)
$recent_documents = getRecentDocuments($db, $user_id, $is_admin, 15);

// 📂 5 Documents Shared With User
try {
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
} catch (PDOException $e) {
    error_log("Shared documents query error: " . $e->getMessage());
    $shared_documents = [];
}

// 📋 Get All Categories
try {
    $categories = $db->prepare("SELECT * FROM categories ORDER BY name");
    $categories->execute();
    $categories = $categories->fetchAll();
} catch (PDOException $e) {
    error_log("Categories query error: " . $e->getMessage());
    $categories = [];
}

// Recent Folders
if (!$is_admin) {
    try {
        $recent_folders_stmt = $db->prepare("
            SELECT f.*, 
                   COUNT(d.id) as document_count
            FROM folders f
            LEFT JOIN documents d ON f.id = d.folder_id AND (d.is_deleted IS NULL OR d.is_deleted = 0)
            GROUP BY f.id
            ORDER BY f.created_at DESC
            LIMIT 4
        ");
        $recent_folders_stmt->execute();
        $recent_folders = $recent_folders_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Recent folders query error: " . $e->getMessage());
        $recent_folders = [];
    }
} else {
    $recent_folders = [];
}

?>