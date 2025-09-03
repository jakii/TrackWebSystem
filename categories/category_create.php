<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAuth();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['category_description'] ?? '');
    $color = $_POST['category_color'] ?? '#004F80';

    if (!$name) {
        $errors[] = "Category name is required.";
    }

    if (empty($errors)) {
        $creatorId = $_SESSION['user_id'];
        $stmt = $db->prepare("INSERT INTO categories (name, description, color, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $color, $creatorId]);

        header('Location: manage.php?success');
        exit;
    }
}
?>
