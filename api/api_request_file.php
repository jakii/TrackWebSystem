<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
  exit;
}

$sender_id = $_SESSION['user_id'];
$recipient_identifier = trim($_POST['recipient_identifier'] ?? '');
$description = trim($_POST['description'] ?? '');
$reason = trim($_POST['reason'] ?? '');

if (empty($recipient_identifier) || empty($description) || empty($reason)) {
  echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
  exit;
}

// Find user by username or email
$stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$recipient_identifier, $recipient_identifier]);
$recipient = $stmt->fetch();

if (!$recipient) {
  echo json_encode(['status' => 'error', 'message' => 'User not found']);
  exit;
}

$recipient_id = $recipient['id'];

// Insert file request
$stmt = $db->prepare("
  INSERT INTO file_requests (sender_id, recipient_id, description, reason, status, created_at)
  VALUES (?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$sender_id, $recipient_id, $description, $reason]);

echo json_encode(['status' => 'success', 'message' => 'File request sent successfully!']);
exit;
