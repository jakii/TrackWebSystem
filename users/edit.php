<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_user.php');
    exit();
}

$id = $_POST['id'] ?? null;
$full_name = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? 'user';
$status = $_POST['status'] ?? 'pending';

if (!$id || !is_numeric($id) || empty($full_name) || empty($username) || empty($email)) {
    header("Location: manage_user.php?error=Please fill all required fields.");
    exit();
}

$stmt = $db->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, role = ?, status = ? WHERE id = ?");
$success = $stmt->execute([$full_name, $username, $email, $role, $status, $id]);

if ($success) {
    header("Location: manage_user.php?success=User updated successfully.");
} else {
    header("Location: manage_user.php?error=Failed to update user.");
}
exit();
