<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
requireAuth();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

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

if (!$sender_id || !$recipient_id || empty($description) || empty($reason)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if ($recipient_id === $sender_id) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot request a file from yourself.']);
    exit;
}

// Verify recipient
$stmt = $db->prepare("SELECT id, email, full_name FROM users WHERE id = ?");
$stmt->execute([$recipient_id]);
$recipient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipient) {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit;
}

// Get sender info
$stmt = $db->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->execute([$sender_id]);
$sender = $stmt->fetch(PDO::FETCH_ASSOC);

// Check for duplicate pending request
$duplicate_check = $db->prepare("
    SELECT id FROM file_requests 
    WHERE sender_id = ? AND recipient_id = ? AND description = ? AND status = 'pending'
");
$duplicate_check->execute([$sender_id, $recipient_id, $description]);
if ($duplicate_check->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(['status' => 'error', 'message' => 'A similar pending request already exists.']);
    exit;
}

// Insert new file request
$stmt = $db->prepare("
    INSERT INTO file_requests (sender_id, recipient_id, description, reason, status, created_at)
    VALUES (?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$sender_id, $recipient_id, $description, $reason]);

// --- Send Email Notification ---
try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'reytabasan123@gmail.com';
    $mail->Password = 'ujhwoulkhmjiekof';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email settings
    $mail->setFrom('reytabasan123@gmail.com', 'Document Request System');
    $mail->addAddress($recipient['email'], $recipient['full_name']);

    $mail->isHTML(true);
    $mail->Subject = "New File Request from {$sender['full_name']}";
    $mail->Body = "
        <p>Hello <strong>{$recipient['full_name']}</strong>,</p>
        <p><strong>{$sender['full_name']}</strong> has requested a file from you.</p>
        <p><strong>Description:</strong> {$description}</p>
        <p><strong>Reason:</strong> {$reason}</p>
        <p>Please log in to your account to review and respond to this request.</p>
        <br>
        <p style='font-size:12px;color:#777;'>This is an automated message from the Document Request System.</p>
    ";

    $mail->send();
} catch (Exception $e) {
    // You can log this if needed but don’t stop the process
    error_log("Email sending failed: " . $mail->ErrorInfo);
}

echo json_encode(['status' => 'success', 'message' => 'File request sent successfully and email notification delivered (if possible).']);
exit;
?>
