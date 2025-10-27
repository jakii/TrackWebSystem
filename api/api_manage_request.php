<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/mailer.php'; // ← PHPMailer config file
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

try {
    // Validate intended date
    $intended_ts = strtotime($intended_date);
    if ($intended_ts < strtotime('today')) {
        echo json_encode(['status' => 'error', 'message' => 'Intended date must be today or later.']);
        exit;
    }

    // Verify recipient
    $stmt = $db->prepare("SELECT id, email, fullname FROM users WHERE id = ?");
    $stmt->execute([$recipient_id]);
    $recipient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipient) {
        echo json_encode(['status' => 'error', 'message' => 'Recipient not found.']);
        exit;
    }

    // Check duplicates
    $duplicate_check = $db->prepare("SELECT id FROM file_requests WHERE sender_id = ? AND recipient_id = ? AND description = ? AND status = 'pending'");
    $duplicate_check->execute([$sender_id, $recipient_id, $description]);
    if ($duplicate_check->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(['status' => 'error', 'message' => 'A similar pending request already exists.']);
        exit;
    }

    // Insert request
    $stmt = $db->prepare("
        INSERT INTO file_requests (sender_id, recipient_id, description, reason, intended_date, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->execute([$sender_id, $recipient_id, $description, $reason, $intended_date]);

    // Send email to document owner
    sendEmail(
        $recipient['email'],
        "File Access Request: {$description}",
        "<p>Hi {$recipient['fullname']},</p>
        <p>You received a new file access request for <strong>{$description}</strong>.</p>
        <p><strong>Reason:</strong> {$reason}</p>
        <p><strong>Intended date of usage:</strong> {$intended_date}</p>
        <p>Please log in to your account to approve or deny this request.</p>"
    );

    echo json_encode(['status' => 'success', 'message' => 'File request sent successfully.']);
    exit;
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
?>
