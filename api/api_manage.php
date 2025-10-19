<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

$error = '';
$success = '';
$csrf_token = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['category_description'] ?? '');
    $color = trim($_POST['category_color'] ?? '#007bff');

    if (empty($name)) {
        $error = 'Category name is required.';
    } else {
        $check_query = $db->prepare("SELECT id FROM categories WHERE name = ?");
        $check_query->execute([$name]);

        if ($check_query->fetch()) {
            $error = 'Category already exists.';
        } else {
            $insert_query = $db->prepare("
                INSERT INTO categories (name, description, color, created_by) 
                VALUES (?, ?, ?, ?)
            ");
            if ($insert_query->execute([$name, $description, $color, $_SESSION['user_id']])) {
                $success = 'Category created successfully.';
                header("Location: manage.php");
                exit();
            } else {
                $error = 'Failed to create category.';
            }
        }
    }
}

$category_list_query = $db->prepare("
    SELECT c.*, 
           COUNT(d.id) as document_count,
           u.full_name as creator_name
    FROM categories c 
    LEFT JOIN documents d ON c.id = d.category_id 
    LEFT JOIN users u ON c.created_by = u.id
    GROUP BY c.id 
    ORDER BY c.name
");
$category_list_query->execute();
$categories = $category_list_query->fetchAll();
?>
