<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
requireAuth();

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$sender_id = $_SESSION['user_id'] ?? null;
if (!$sender_id) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$recipient_identifier = trim($_POST['recipient_identifier'] ?? '');
$description = trim($_POST['description'] ?? '');
$reason = trim($_POST['reason'] ?? '');

if (empty($recipient_identifier) || empty($description) || empty($reason)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$recipient_identifier, $recipient_identifier]);
$recipient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipient) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

$recipient_id = $recipient['id'];

if ($recipient_id === $sender_id) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot request a file from yourself.']);
    exit;
}

$duplicate_check = $db->prepare("SELECT id FROM file_requests WHERE sender_id = ? AND recipient_id = ? AND description = ? AND status = 'pending'");
$duplicate_check->execute([$sender_id, $recipient_id, $description]);
if ($duplicate_check->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(['status' => 'error', 'message' => 'A similar pending request already exists.']);
    exit;
}

$stmt = $db->prepare("
    INSERT INTO file_requests (sender_id, recipient_id, description, reason, status, created_at)
    VALUES (?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$sender_id, $recipient_id, $description, $reason]);

echo json_encode(['status' => 'success', 'message' => 'File request sent successfully']);
exit;
?>