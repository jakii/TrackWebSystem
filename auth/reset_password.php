<?php
require_once '../config/database.php';
$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check token validity
    $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($new_password === $confirm_password && !empty($new_password)) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);

                // Update user password
                $update = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->execute([$hashed, $reset['user_id']]);

                // Delete the used token
                $delete = $db->prepare("DELETE FROM password_resets WHERE token = ?");
                $delete->execute([$token]);

                $message = "Password has been reset successfully. <a href='login.php'>Login here</a>.";
            } else {
                $message = "Passwords do not match or are empty.";
            }
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    $message = "No reset token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/LOGO.png">
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <h1 class="login-title">Reset Password</h1>
        <?php if ($message): ?>
            <div class="alert-custom"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (isset($reset) && $reset): ?>
            <form method="POST">
                <div class="mb-4">
                    <label>New Password</label>
                    <input name="new_password" class="form-control-custom" required>
                </div>
                <div class="mb-4">
                    <label>Confirm Password</label>
                    <input name="confirm_password" class="form-control-custom" required>
                </div>
                <button type="submit" class="btn-submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>