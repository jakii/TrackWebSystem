<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $document_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$document_id) {
        header("Location: ../dashboard.php?error=" . urlencode('Invalid document ID.'));
        exit();
    }

    // --- SHARE DOCUMENT ---
    if (isset($_POST['share_document'])) {
        $share_with_email = trim($_POST['share_with'] ?? '');
        $permission = $_POST['permission'] ?? 'view';

        if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
            if (!empty($share_with_email)) {
                // Lookup by email only
                $user_lookup_query = $db->prepare("SELECT id, email, full_name FROM users WHERE email = ?");
                $user_lookup_query->execute([$share_with_email]);
                $share_user = $user_lookup_query->fetch();

                if ($share_user && $share_user['id'] != $_SESSION['user_id']) {
                    // Check if already shared
                    $existing_share_query = $db->prepare("
                        SELECT id FROM document_shares 
                        WHERE document_id = ? AND shared_with = ?
                    ");
                    $existing_share_query->execute([$document_id, $share_user['id']]);

                    if (!$existing_share_query->fetch()) {
                        // Insert new share record
                        $insert_share_query = $db->prepare("
                            INSERT INTO document_shares (document_id, shared_with, shared_by, permission) 
                            VALUES (?, ?, ?, ?)
                        ");
                        $insert_share_query->execute([$document_id, $share_user['id'], $_SESSION['user_id'], $permission]);

                        // Log activity
                        $user_id = $_SESSION['user_id'];
                        $doc_title = $document['title'] ?? 'Unknown';
                        $shared_to = $share_user['full_name'] ?? $share_user['email'];
                        logActivity($db, $user_id, "Shared document: {$doc_title} (ID: {$document_id}) to {$shared_to} with permission: {$permission}");

                        // --- Get current user's name for email ---
                        $current_user_stmt = $db->prepare("SELECT full_name FROM users WHERE id = ?");
                        $current_user_stmt->execute([$user_id]);
                        $current_user = $current_user_stmt->fetch();
                        $current_user_name = $current_user['full_name'] ?? 'Someone';

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

                            $mail->setFrom('reytabasan123@gmail.com', 'TVET Record System');
                            $mail->addAddress($share_user['email'], $share_user['full_name'] ?? '');

                            $mail->isHTML(true);
                            $mail->Subject = "A Document Has Been Shared With You";
                            $mail->Body = "
                                <p>Hello <strong>{$share_user['full_name']}</strong>,</p>
                                <p>{$current_user_name} has shared the document <strong>{$doc_title}</strong> with you.</p>
                                <p>Permission: <b>{$permission}</b></p>
                                <p><a href='https://csutrack.site/documents/shared.php'>
                                    Click here to view the document
                                </a></p>
                                <p style='font-size:12px;color:#777'>TVET Record and Archival Control Kiosk</p>
                            ";

                            $mail->send();
                        } catch (Exception $e) {
                            error_log("Email failed: " . $mail->ErrorInfo);
                        }
                    }
                }
            }
        }
        header("Location: share.php?id=$document_id");
        exit();
    }

    // --- UNSHARE DOCUMENT ---
    if (isset($_POST['unshare_document'])) {
        $share_id = (int)$_POST['share_id'];
        if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $delete_share_query = $db->prepare("
                DELETE FROM document_shares 
                WHERE id = ? AND shared_by = ?
            ");
            $delete_share_query->execute([$share_id, $_SESSION['user_id']]);

            $user_id = $_SESSION['user_id'];
            $doc_title = $document['title'] ?? 'Unknown';
            logActivity($db, $user_id, "Unshared document: {$doc_title}");
        }
        header("Location: share.php?id=$document_id");
        exit();
    }
}
?>
