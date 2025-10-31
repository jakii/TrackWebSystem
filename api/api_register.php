<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/activity_logger.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if (isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Validation ---
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = 'Password must be at least 8 characters long and include an uppercase letter, 
                  a lowercase letter, a number, and a special character.';
    } else {
        // --- Check if user/email already exists ---
        $check_user_query = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_user_query->execute([$username, $email]);

        if ($check_user_query->fetch()) {
            $error = 'Username or email already exists.';
        } else {
            // --- Create new user (unverified/pending) ---
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $status = 'unverified';

            $insert_user_query = $db->prepare("
                INSERT INTO users (username, email, password, full_name, role, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            if ($insert_user_query->execute([$username, $email, $hashed_password, $full_name, $role, $status])) {
                $user_id = $db->lastInsertId();
                logActivity($db, $user_id, 'User registered (awaiting email verification).');

                // --- Generate OTP ---
                $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                // --- Save OTP ---
                $save_otp = $db->prepare("
                    INSERT INTO email_otps (user_id, otp_code, expires_at, is_used)
                    VALUES (?, ?, ?, 0)
                ");
                $save_otp->execute([$user_id, $otp, $expires_at]);

                // --- Send OTP via email ---
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
                    $mail->addAddress($email, $full_name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Email Verification Code';
                    $mail->Body = "
                        <h2>Hello, $full_name!</h2>
                        <p>Use the OTP below to verify your email:</p>
                        <h1 style='letter-spacing:4px;'>$otp</h1>
                        <p>This code will expire in 5 minutes.</p>
                    ";
                    $mail->send();

                    // --- Redirect to OTP verification page ---
                    header("Location: ../auth/verify_otp.php?email=" . urlencode($email));
                    exit;
                } catch (Exception $e) {
                    $error = "Registration successful but failed to send OTP: " . $mail->ErrorInfo;
                }
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
