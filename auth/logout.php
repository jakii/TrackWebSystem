<?php
require_once __DIR__ . '/../config/config.php';

// Store user data before session destruction
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Unknown';

// Log activity and update database BEFORE session destruction
if (!empty($user_id)) {
    try {
        // Log logout activity
        safeLogActivity($db, $user_id, 'User logged out: ' . $username);
        
        // Update login status in database
        $stmt = $db->prepare("UPDATE users SET is_logged_in = 0, last_logout = NOW() WHERE id = ?");
        $stmt->execute([$user_id]);
        
        error_log("Logout successful for user: " . $username);
    } catch (Exception $e) {
        error_log("Logout process error: " . $e->getMessage());
        // Continue with logout even if logging fails
    }
} else {
    error_log("Logout attempted without valid user_id");
}

// Complete session cleanup
$_SESSION = [];

// Destroy session completely
if (session_id()) {
    session_destroy();
}

// Clear session cookie - works for both environments
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, 
    $params["path"], 
    $params["domain"],
    $params["secure"], 
    $params["httponly"]
);

// Additional cleanup - clear all session-related cookies
setcookie(session_name(), '', time() - 3600, '/');

// Clear any custom session cookies
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/', '', IS_HTTPS, true);
}

// Redirect to login page - universal function handles both environments
$login_url = BASE_URL . 'auth/login.php';
redirect($login_url);

// Emergency exit if redirect fails
exit('Redirect failed. Please <a href="' . $login_url . '">click here</a> to continue.');
?>