<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_user.php?error=Invalid user ID.");
    exit();
}

$userId = (int) $_GET['id'];

$stmt = $db->prepare("SELECT id, status FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: manage_user.php?error=User not found.");
    exit();
}

if ($user['status'] === 'active') {
    header("Location: users.php?error=User is already active.");
    exit();
}

$stmt = $db->prepare("UPDATE users SET status = 'active' WHERE id = ?");
$stmt->execute([$userId]);

header("Location: manage_user.php?success=User approved successfully.");
exit();
