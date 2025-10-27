<?php
// includes/email_function.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $subject, $htmlBody, $altBody = '') {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings — update to your SMTP provider
        $mail->isSMTP();
        $mail->Host = 'smtp.csutrack.site';
        $mail->SMTPAuth = true;
        $mail->Username = 'reytabasan123@gmail.com';
        $mail->Password = 'ujhwoulkhmjiekof';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('reytabasan123@gmail.com', 'TrackWeb');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody ?: strip_tags($htmlBody);

        return $mail->send();
    } catch (Exception $e) {
        // Log error somewhere if you have logger
        error_log("Email error: {$mail->ErrorInfo}");
        return false;
    }
}