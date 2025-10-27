<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/email_function.php'; // <-- for email sending
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
$recipient_id = intval($_POST['recipient_id'] ?? 0);
$description = trim($_POST['description'] ?? '');
$reason = trim($_POST['reason'] ?? '');
$intended_date = trim($_POST['intended_date'] ?? '');

if (!$sender_id || !$recipient_id || empty($description) || empty($reason) || empty($intended_date)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if ($recipient_id === $sender_id) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot request a file from yourself.']);
    exit;
}

// Validate intended date (must be today or future)
$today = date('Y-m-d');
if ($intended_date < $today) {
    echo json_encode(['status' => 'error', 'message' => 'Intended date must be today or a future date.']);
    exit;
}

// Check if recipient exists
$stmt = $db->prepare("SELECT id, email, full_name FROM users WHERE id = ?");
$stmt->execute([$recipient_id]);
$recipient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipient) {
    echo json_encode(['status' => 'error', 'message' => 'Recipient not found.']);
    exit;
}

// Prevent duplicate pending requests
$duplicate_check = $db->prepare("
    SELECT id FROM file_requests 
    WHERE sender_id = ? 
      AND recipient_id = ? 
      AND description = ? 
      AND status = 'pending'
");
$duplicate_check->execute([$sender_id, $recipient_id, $description]);
if ($duplicate_check->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(['status' => 'error', 'message' => 'A similar pending request already exists.']);
    exit;
}

// Insert new request
$stmt = $db->prepare("
    INSERT INTO file_requests (sender_id, recipient_id, description, reason, intended_date, status, created_at)
    VALUES (?, ?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$sender_id, $recipient_id, $description, $reason, $intended_date]);

// Send email notification to recipient
$sender_query = $db->prepare("SELECT full_name, email FROM users WHERE id = ?");
$sender_query->execute([$sender_id]);
$sender = $sender_query->fetch(PDO::FETCH_ASSOC);

if ($sender && $recipient) {
    $subject = "New File Request from {$sender['full_name']}";
    $body = "
        <h3>📄 New File Request</h3>
        <p><b>From:</b> {$sender['full_name']} ({$sender['email']})</p>
        <p><b>Description:</b> {$description}</p>
        <p><b>Purpose:</b> {$reason}</p>
        <p><b>Intended Date of Usage:</b> {$intended_date}</p>
        <hr>
        <p>Please log in to your account to approve or deny this request before <b>{$intended_date}</b>.</p>
    ";

    sendEmail($recipient['email'], $subject, $body);
}

echo json_encode(['status' => 'success', 'message' => 'File request sent successfully. Notification email sent.']);
exit;
?>
