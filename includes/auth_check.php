<?php
require_once __DIR__ . '/../config/config.php';

// Ensure database connection
if (!isset($db) || $db === null) {
    require_once __DIR__ . '/../config/database.php';
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }
}

// Check admin roles
function isAdmin() {
    return isset($_SESSION['user_role']) && 
           in_array($_SESSION['user_role'], ['admin', 'superadmin'], true);
}

// Auth required check
function requireAuth() {
    global $db;

    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit();
    }

    // Validate user existence
    $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit();
    }

    // Check for session timeout
    $timeout = defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 3600;
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $timeout)) {
        $update = $db->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
        $update->execute([$_SESSION['user_id']]);

        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login.php?session=expired');
        exit();
    } else {
        $_SESSION['login_time'] = time(); // refresh session
    }
}

// Admin-only pages
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . 'dashboard.php?error=access_denied');
        exit();
    }
}

// Fetch current user info
function getCurrentUser() {
    global $db;

    if (!isLoggedIn()) return null;

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
?>
