<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../documents/shared.php?status=error');
  exit;
}

$request_id = intval($_POST['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if (!$request_id) {
  header('Location: ../documents/shared.php?status=invalid');
  exit;
}

// Only allow deletion if request is denied and user is either sender or admin
$stmt = $db->prepare("
  SELECT sender_id, status FROM file_requests WHERE id = ?
");
$stmt->execute([$request_id]);
$request = $stmt->fetch();

if (!$request) {
  header('Location: ../documents/shared.php?status=notfound');
  exit;
}

if ($request['status'] !== 'denied' && !isAdmin()) {
  header('Location: ../documents/shared.php?status=forbidden');
  exit;
}

if ($request['sender_id'] != $user_id && !isAdmin()) {
  header('Location: ../documents/shared.php?status=unauthorized');
  exit;
}

// Delete the denied request
$stmt = $db->prepare("DELETE FROM file_requests WHERE id = ?");
$stmt->execute([$request_id]);

header('Location: ../documents/shared.php?status=deleted');
exit;
