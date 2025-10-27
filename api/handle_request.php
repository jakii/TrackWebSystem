<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/mailer.php'; // PHPMailer config
requireAuth();

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

// Get request details
$stmt = $db->prepare("
    SELECT fr.*, u.email AS sender_email, u.fullname AS sender_name 
    FROM file_requests fr
    JOIN users u ON u.id = fr.sender_id
    WHERE fr.id = ? AND fr.recipient_id = ?
");
$stmt->execute([$request_id, $user_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    header('Location: ../documents/shared.php?status=notfound');
    exit;
}

// Check date restriction
$today = strtotime('today');
$intended_ts = strtotime($request['intended_date']);

if ($action === 'approve' && $intended_ts < $today) {
    header('Location: ../documents/shared.php?status=expired');
    exit;
}

// Process actions
if ($action === 'approve') {
    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'approved', approved_at = NOW() WHERE id = ?");
    $update_stmt->execute([$request_id]);

    // Notify requester
    sendEmail(
        $request['sender_email'],
        "Request Approved: {$request['description']}",
        "<p>Hi {$request['sender_name']},</p>
         <p>Your file request for <strong>{$request['description']}</strong> has been approved.</p>
         <p>You may now access the file as requested (intended usage until {$request['intended_date']}).</p>"
    );

    header('Location: ../documents/shared.php?status=approved');
    exit;
}

if ($action === 'deny') {
    $deny_reason = trim($_POST['deny_reason'] ?? '');
    if (empty($deny_reason)) {
        header('Location: ../documents/shared.php?status=missing_reason');
        exit;
    }

    $update_stmt = $db->prepare("UPDATE file_requests SET status = 'denied', deny_reason = ?, denied_at = NOW() WHERE id = ?");
    $update_stmt->execute([$deny_reason, $request_id]);

    // Notify requester
    sendEmail(
        $request['sender_email'],
        "Request Denied: {$request['description']}",
        "<p>Hi {$request['sender_name']},</p>
         <p>Your file request for <strong>{$request['description']}</strong> has been <strong>denied</strong>.</p>
         <p><strong>Reason:</strong> {$deny_reason}</p>"
    );

    header('Location: ../documents/shared.php?status=denied');
    exit;
}
?>
