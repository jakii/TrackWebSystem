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
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$sender_id     = $_SESSION['user_id'];
$recipient_id  = $_POST['recipient_id'] ?? null;
$reason        = trim($_POST['reason'] ?? '');
$document_id   = $_POST['document_id'] ?? null;
$intended_date = $_POST['intended_date'] ?? null;

if (!$recipient_id || !$reason || !$document_id || !$intended_date) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!DateTime::createFromFormat('Y-m-d', $intended_date)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid date format.']);
    exit;
}

try {
    // Get document details
    $doc_stmt = $db->prepare("SELECT title FROM documents WHERE id = ?");
    $doc_stmt->execute([$document_id]);
    $document = $doc_stmt->fetch();

    if (!$document) {
        echo json_encode(['status' => 'error', 'message' => 'Document not found.']);
        exit;
    }

    // Insert file request
    $stmt = $db->prepare("
        INSERT INTO file_requests (sender_id, recipient_id, document_id, reason, intended_date, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->execute([$sender_id, $recipient_id, $document_id, $reason, $intended_date]);

    // Get sender and recipient info
    $user_stmt = $db->prepare("SELECT id, full_name, email FROM users WHERE id IN (?, ?)");
    $user_stmt->execute([$sender_id, $recipient_id]);
    $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);

    $sender = null;
    $recipient = null;
    foreach ($users as $u) {
        if ($u['id'] == $sender_id) $sender = $u;
        if ($u['id'] == $recipient_id) $recipient = $u;
    }

    // Send email notification
    if ($recipient && !empty($recipient['email'])) {
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'reytabasan123@gmail.com';
            $mail->Password   = 'ujhwoulkhmjiekof';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Sender and recipient
            $mail->setFrom('reytabasan123@gmail.com', 'Document Tracker');
            $mail->addAddress($recipient['email'], $recipient['full_name']);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "New File Request from {$sender['full_name']}";
            $mail->Body = "
                <div style='font-family:Arial, sans-serif; color:#333;'>
                    <h3>You have a new file request!</h3>
                    <p><strong>From:</strong> {$sender['full_name']}</p>
                    <p><strong>Requested Document:</strong> {$document['title']}</p>
                    <p><strong>Purpose:</strong> {$reason}</p>
                    <p><strong>Intended Date of Usage:</strong> " . date('F d, Y', strtotime($intended_date)) . "</p>
                    <hr>
                    <p>Please log in to your account to approve or deny the request.</p>
                    <a href='" . BASE_URL . "documents/shared.php' 
                       style='display:inline-block;background:#004F80;color:#fff;padding:10px 20px;
                       text-decoration:none;border-radius:5px;'>View Request</a>
                </div>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Email failed: " . $mail->ErrorInfo);
        }
    }

    // Return success message for frontend alert
    echo json_encode([
        'status' => 'success',
        'message' => 'File request sent successfully! An email notification was sent to the recipient.'
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>