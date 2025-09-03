<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_user.php?error=Invalid user ID.");
    exit();
}

if (!isset($_GET['action']) || !in_array($_GET['action'], ['enable', 'disable'])) {
    header("Location: manage_user.php?error=Invalid action.");
    exit();
}

$userId = (int) $_GET['id'];
$action = $_GET['action'];

// Get user
$stmt = $db->prepare("SELECT id, status, role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: manage_user.php?error=User not found.");
    exit();
}

// Prevent disabling admin
if ($user['role'] === 'admin') {
    header("Location: manage_user.php?error=Cannot change status of admin users.");
    exit();
}

if ($action === 'enable') {
    if ($user['status'] === 'active') {
        header("Location: manage_user.php?error=User is already active.");
        exit();
    }
    $newStatus = 'active';
} else { // disable
    if ($user['status'] === 'disabled') {
        header("Location: manage_user.php?error=User is already disabled.");
        exit();
    }
    $newStatus = 'disabled';
}

// Update status
$stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
$stmt->execute([$newStatus, $userId]);

header("Location: manage_user.php?success=User status updated successfully.");
exit();
