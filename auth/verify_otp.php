<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/activity_logger.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$email = $_GET['email'] ?? ($_POST['email'] ?? '');
$error = '';
$success = '';

if (isset($_POST['verify'])) {
    $otp = trim($_POST['otp'] ?? '');

    $stmt = $db->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $db->prepare("
            SELECT * FROM email_otps
            WHERE user_id = ? AND otp_code = ? AND is_used = 0 AND expires_at > NOW()
        ");
        $stmt->execute([$user['id'], $otp]);
        $otpRow = $stmt->fetch();

        if ($otpRow) {
            $db->prepare("UPDATE users SET status = 'active', is_verified = 1 WHERE id = ?")->execute([$user['id']]);
            $db->prepare("UPDATE email_otps SET is_used = 1 WHERE id = ?")->execute([$otpRow['id']]);
            logActivity($db, $user['id'], 'Email verified via OTP.');

            $success = 'Email verified success! You can now <a href="login.php">login</a>.';
        } else {
            $error = 'Invalid or expired OTP.';
        }
    } else {
        $error = 'User not found.';
    }
}

if (isset($_POST['resend'])) {
    $stmt = $db->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $db->prepare("UPDATE email_otps SET is_used = 1 WHERE user_id = ?")->execute([$user['id']]);

        $stmt = $db->prepare("INSERT INTO email_otps (user_id, otp_code, expires_at, is_used) VALUES (?, ?, ?, 0)");
        $stmt->execute([$user['id'], $otp, $expires_at]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'reytabasan123@gmail.com';
            $mail->Password = 'ujhwoulkhmjiekof';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('reytabasan123@gmail.com', 'Track');
            $mail->addAddress($email, $user['full_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Your New OTP Code';
            $mail->Body = "
                <h2>Hello, {$user['full_name']}!</h2>
                <p>Your new OTP code is:</p>
                <h1 style='letter-spacing:4px;'>$otp</h1>
                <p>This code will expire in 5 minutes.</p>
            ";
            $mail->send();

            $success = 'A new OTP has been sent to your email.';
            logActivity($db, $user['id'], 'Resent OTP for email verification.');
        } catch (Exception $e) {
            $error = 'Failed to resend OTP: ' . $mail->ErrorInfo;
        }
    } else {
        $error = 'User not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <link rel="stylesheet" href="../assets/css/login.css">
  <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/LOGO.png">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <h2>Email Verification</h2>
      <p>Enter the 6-digit OTP sent to <b><?= htmlspecialchars($email) ?></b></p>
      <br>
      <?php if ($error): ?>
        <div class="alert-custom"><?= $error ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert-success"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" class="otp-form">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="text" name="otp" maxlength="6" placeholder="Enter 6-digit OTP" class="form-control-custom" required>
        <button type="submit" name="verify" class="btn-submit"style="margin-top:15px;">Verify</button>
      </form>

      <form method="POST" class="resend-form" style="margin-top:8px;">
        <input type="hidden" name="email" class="form-control-custom" value="<?= htmlspecialchars($email) ?>">
        <button type="submit" name="resend" class="btn-submit">Resend OTP</button>
      </form>
    </div>
  </div>
</body>
</html>
