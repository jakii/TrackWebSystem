<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../documents/shared.php?status=error');
    exit;
}

$request_id = intval($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

if (!$request_id || !in_array($action, ['approve', 'deny'])) {
    header('Location: ../documents/shared.php?status=invalid');
    exit;
}

if (!$db->prepare("SELECT id FROM file_requests WHERE id = ? AND recipient_id = ?")->execute([$request_id, $user_id])->fetch()) {
    header('Location: ../documents/shared.php?status=notfound');
    exit;
}

$db->prepare("UPDATE file_requests SET status = ? WHERE id = ?")->execute([$action === 'approve' ? 'approved' : 'denied', $request_id]);

header('Location: ../documents/shared.php?status=success');
exit;
