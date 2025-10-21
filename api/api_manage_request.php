<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
requireAuth();

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../documents/shared.php?status=error');
    exit;
}

$request_id = intval($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

$valid_actions = ['approve', 'deny'];
if (!$request_id || !in_array($action, $valid_actions)) {
    header('Location: ../documents/shared.php?status=invalid');
    exit;
}

$check_stmt = $db->prepare("SELECT id FROM file_requests WHERE id = ? AND recipient_id = ?");
$check_stmt->execute([$request_id, $user_id]);
$request_exists = $check_stmt->fetch(PDO::FETCH_ASSOC);

if (!$request_exists) {
    header('Location: ../documents/shared.php?status=notfound');
    exit;
}

if ($action === 'delete') {
    header('Location: ../documents/shared.php?status=nodelete');
    exit;
}

if ($action === 'approve') {
    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'approved', updated_at = NOW() WHERE id = ?");
    $update_stmt->execute([$request_id]);
    header('Location: ../documents/shared.php?status=approved');
    exit;
}

if ($action === 'deny') {
    $deny_reason = trim($_POST['deny_reason'] ?? '');
    if (empty($deny_reason)) {
        header('Location: ../documents/shared.php?status=missing_reason');
        exit;
    }
    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'denied', deny_reason = ?, updated_at = NOW() WHERE id = ?");
    $update_stmt->execute([$deny_reason, $request_id]);
    header('Location: ../documents/shared.php?status=denied');
    exit;
}
?>
