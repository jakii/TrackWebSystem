<?php
require_once __DIR__ . '/../config/config.php';

// Check if user is logged in before attempting to log activity
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Log logout activity before destroying session
    logActivity($db, $user_id, 'User logged out.');
    
    // Update login status in database
    $stmt = $db->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Clear all session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../auth/login.php');
exit();
?>