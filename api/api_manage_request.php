<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
requireAuth();

// Include PHPMailer classes
require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../documents/shared.php?status=error');
    exit;
}

$request_id = intval($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

$valid_actions = ['approve', 'deny'];
if (!$request_id || !in_array($action, $valid_actions)) {
    header('Location: ../documents/shared.php?status=invalid');
    exit;
}

// Verify request exists and fetch requester info
$check_stmt = $db->prepare("
    SELECT fr.id, fr.sender_id, fr.status, u.email, u.full_name
    FROM file_requests fr
    JOIN users u ON fr.sender_id = u.id
    WHERE fr.id = ? AND fr.recipient_id = ?
");
$check_stmt->execute([$request_id, $user_id]);
$request = $check_stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    header('Location: ../documents/shared.php?status=notfound');
    exit;
}

$requester_email = $request['email'];
$requester_name = $request['full_name'];

function sendNotification($to, $toName, $subject, $messageHtml)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'reytabasan123@gmail.com';
        $mail->Password = 'ujhwoulkhmjiekof';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender info
        $mail->setFrom('reytabasan123@gmail.com', 'File Request System');
        $mail->addAddress($to, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $messageHtml;
        $mail->AltBody = strip_tags($messageHtml);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($action === 'approve') {
    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'approved' WHERE id = ?");
    $update_stmt->execute([$request_id]);

    $subject = "Your File Request has been Approved";
    $message = "
        <p>Hi {$requester_name},</p>
        <p>Your file request (#{$request_id}) has been <strong>approved</strong>.</p>
        <p>You can now access the file in your account.</p>
        <p style='font-size:12px;color:#777'>TVET Record and Archival Control Kiosk</p>
    ";

    sendNotification($requester_email, $requester_name, $subject, $message);

    header('Location: ../documents/shared.php?status=approved');
    exit;
}

if ($action === 'deny') {
    $deny_reason = trim($_POST['deny_reason'] ?? '');
    if (empty($deny_reason)) {
        header('Location: ../documents/shared.php?status=missing_reason');
        exit;
    }

    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'denied', deny_reason = ? WHERE id = ?");
    $update_stmt->execute([$deny_reason, $request_id]);

    $subject = "Your File Request has been Denied";
    $message = "
        <p>Hi {$requester_name},</p>
        <p>Your file request (#{$request_id}) has been <strong>denied</strong>.</p>
        <p><strong>Reason:</strong> {$deny_reason}</p>
        <p>If you believe this was an error, please contact the admin.</p>
        <p style='font-size:12px;color:#777'>TVET Record and Archival Control Kiosk</p>
    ";

    sendNotification($requester_email, $requester_name, $subject, $message);

    header('Location: ../documents/shared.php?status=denied');
    exit;
}
?>
