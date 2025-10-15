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

$status = $action === 'approve' ? 'approved' : 'denied';

$stmt = $db->prepare("UPDATE file_requests SET status = ? WHERE id = ? AND recipient_id = ?");
$stmt->execute([$status, $request_id, $user_id]);

header('Location: ../documents/shared.php?status=success');
exit;
