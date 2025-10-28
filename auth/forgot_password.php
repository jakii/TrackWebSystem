<?php
require_once '../config/database.php';
require_once '../includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!empty($email)) {
        // Check if email exists
        $stmt = $db->prepare("SELECT id, full_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate reset token and expiry
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Save reset token
            $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $token, $expires]);

            // Reset link
            $reset_link = "https://csutrack.site/auth/reset_password.php?token=$token";

            // Send email via PHPMailer
            $mail = new PHPMailer(true);
            try {
                // SMTP Settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'reytabasan123@gmail.com';
                $mail->Password = 'ujhwoulkhmjiekof';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Sender and recipient
                $mail->setFrom('reytabasan123@gmail.com', 'TrackWeb Support');
                $mail->addAddress($email, $user['full_name']);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "
                    <p>Hi <strong>{$user['full_name']}</strong>,</p>
                    <p>You recently requested to reset your password for your account.</p>
                    <p>Click the link below to reset it. This link will expire in 1 hour:</p>
                    <p><a href='$reset_link'>$reset_link</a></p>
                    <p>If you didn't request this, please ignore this email.</p>
                    <br>
                    <p>— TrackWeb Support Team</p>
                ";
                $mail->SMTPDebug = 2;
                $mail->send();
                $message = "A password reset link has been sent to your email.";
            } catch (Exception $e) {
                $message = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "No account found with that email.";
        }
    } else {
        $message = "Please enter your email.";
    }
}
?>

<link rel="stylesheet" href="../assets/css/login.css">

<div class="login-container">
    <div class="login-card">
        <h1 class="login-title">Forgot Password</h1>
        <p class="login-subtitle">Enter your email to receive a password reset link</p>

        <?php if ($message): ?>
            <div class="alert-custom"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="email" class="form-label-custom">Email</label>
                <input 
                    type="email" 
                    class="form-control-custom" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                >
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
            </button>

            <div class="text-center mt-3">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
</div>
