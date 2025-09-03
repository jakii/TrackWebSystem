<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once '../includes/activity_logger.php';

if (isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user_query = $db->prepare("
            SELECT id, username, email, password, full_name, role, status
            FROM users 
            WHERE username = ? OR email = ?
        ");
        $user_query->execute([$username, $username]);
        $user = $user_query->fetch();
        
        if ($user && password_verify($password, $user['password'])) {

            if ($user['status'] === 'disabled') {
                $error = 'Your account has been disabled. Please contact the administrator.';
            } elseif ($user['status'] !== 'active') {
                $error = 'Your account is pending approval by the admin.';
            } else {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
            
                // Log activity
                logActivity($db, $user['id'], 'User logged in.');
            
                // Mark as logged in
                $stmt = $db->prepare("UPDATE users SET is_logged_in = 1 WHERE id = ?");
                $stmt->execute([$user['id']]);
            
                header('Location: ../dashboard.php');
                exit();
            }
        
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
