<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once '../includes/activity_logger.php';

// Check if already logged in
if (isLoggedIn()) {
    redirect('../dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user_query = $db->prepare("
            SELECT id, username, email, password, full_name, role, status, is_logged_in
            FROM users 
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $user_query->execute([$username, $username]);
        $user = $user_query->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Account status checks
            if ($user['status'] === 'disabled') {
                $error = 'Your account has been disabled. Please contact the administrator.';
            } elseif ($user['status'] !== 'active') {
                $error = 'Your account is pending approval by the admin.';
            } else {
                // ✅ Regenerate session to prevent fixation
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            
                // Log activity with proper validation
                if (!empty($user['id'])) {
                    safeLogActivity($db, $user['id'], 'User logged in from ' . $_SERVER['REMOTE_ADDR']);
                }
            
                // Mark as logged in
                $stmt = $db->prepare("UPDATE users SET is_logged_in = 1, last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
            
                // Successful login redirect
                redirect('../dashboard.php');
            }
        } else {
            $error = 'Invalid username or password.';
            
            // Log failed login attempt
            error_log("Failed login attempt for username: " . $username . " from IP: " . $_SERVER['REMOTE_ADDR']);
        }
    }
}

// If we reach here, show login form with error
// This part depends on your login.php form structure
?>