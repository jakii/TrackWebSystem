<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

$error = '';
$success = '';

//category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $color = trim($_POST['color'] ?? '#007bff');

    if (empty($name)) {
        $error = 'Category name is required.';
    } else {
        //check if category already exists
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
            } else {
                $error = 'Failed to create category.';
            }
        }
    }
}

//category deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = (int)$_POST['category_id'];

    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $doc_check_query = $db->prepare("SELECT COUNT(*) as count FROM documents WHERE category_id = ?");
        $doc_check_query->execute([$category_id]);
        $doc_count = $doc_check_query->fetch()['count'] ?? 0;

        if ($doc_count > 0) {
            $error = 'Cannot delete category with existing documents. Move or delete documents first.';
        } else {
            $delete_query = $db->prepare("DELETE FROM categories WHERE id = ?");
            if ($delete_query->execute([$category_id])) {
                $success = 'Category deleted successfully.';
            } else {
                $error = 'Failed to delete category.';
            }
        }
    } else {
        $error = 'Invalid security token.';
    }
}

//get all categories
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