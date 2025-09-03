<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_user.php');
    exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';
$status = $_POST['status'] ?? 'pending';

if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
    header('Location: manage_user.php?error=Please fill all required fields.');
    exit();
}

// Check if username or email already exists
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    header('Location: manage_user.php?error=Username or Email already exists.');
    exit();
}

// Hash password securely
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (full_name, username, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$inserted = $stmt->execute([$full_name, $username, $email, $passwordHash, $role, $status]);

if ($inserted) {
    header('Location: manage_user.php?success=User added successfully.');
} else {
    header('Location: manage_user.php?error=Failed to add user.');
}
exit();
