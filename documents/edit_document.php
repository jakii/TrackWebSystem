<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = $_POST['category_id'] ?: null;
    $tags = trim($_POST['tags'] ?? '');
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    if (!$id || !$title) {
        $_SESSION['error'] = 'Invalid input.';
        header("Location: view.php?id=$id");
        exit();
    }

    $stmt = $db->prepare("UPDATE documents SET title = ?, description = ?, category_id = ?, tags = ?, is_public = ? WHERE id = ?");
    $stmt->execute([$title, $description, $category_id, $tags, $is_public, $id]);

    $_SESSION['success'] = 'Document details updated successfully.';
    header("Location: view.php?id=$id");
    exit();
}

header("Location: ../dashboard.php");
exit();
