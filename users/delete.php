<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_user.php');
    exit();
}

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: manage_user.php?error=Invalid user ID.");
    exit();
}

$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$success = $stmt->execute([$id]);

if ($success) {
    header("Location: manage_user.php?success=User deleted successfully.");
} else {
    header("Location: manage_user.php?error=Failed to delete user.");
}
exit();
