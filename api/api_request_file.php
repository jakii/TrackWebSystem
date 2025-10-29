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
$document_id   = $_POST['document_id'] ?? null;
$reason        = trim($_POST['reason'] ?? '');
$intended_date = $_POST['intended_date'] ?? null;

if (!$document_id || !$reason || !$intended_date) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!DateTime::createFromFormat('Y-m-d', $intended_date)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid date format.']);
    exit;
}

try {
    //Get document + uploader info
    $doc_stmt = $db->prepare("
        SELECT d.title, u.id AS uploader_id, u.full_name AS uploader_name, u.email AS uploader_email
        FROM documents d
        JOIN users u ON d.uploaded_by = u.id
        WHERE d.id = ?
    ");
    $doc_stmt->execute([$document_id]);
    $document = $doc_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$document) {
        echo json_encode(['status' => 'error', 'message' => 'Document not found.']);
        exit;
    }

    $recipient_id = $document['uploader_id'];

    //Insert file request record
    $stmt = $db->prepare("
        INSERT INTO file_requests (sender_id, recipient_id, document_id, reason, intended_date, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->execute([$sender_id, $recipient_id, $document_id, $reason, $intended_date]);

    //Get sender info
    $sender_stmt = $db->prepare("SELECT full_name, email FROM users WHERE id = ?");
    $sender_stmt->execute([$sender_id]);
    $sender = $sender_stmt->fetch(PDO::FETCH_ASSOC);

    //Send email to the uploader
    if (!empty($document['uploader_email'])) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'reytabasan123@gmail.com';
            $mail->Password   = 'ujhwoulkhmjiekof';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email headers
            $mail->setFrom('reytabasan123@gmail.com', 'Document Tracker');
            $mail->addAddress($document['uploader_email'], $document['uploader_name']);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "File Request: {$document['title']}";
            $mail->Body = "
                <div style='font-family:Arial, sans-serif; color:#333;'>
                    <h3>New File Request Received</h3>
                    <p><strong>From:</strong> {$sender['full_name']} ({$sender['email']})</p>
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
            error_log("Email send error: " . $mail->ErrorInfo);
        }
    }

    // Response to frontend
    echo json_encode([
        'status' => 'success',
        'message' => 'File request sent successfully! The document uploader was notified by email.'
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
