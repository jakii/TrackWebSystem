<?php
require_once __DIR__ . '/../config/config.php';

//Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if isLoggedIn() is already declared
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }
}

//Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && 
           in_array($_SESSION['user_role'], ['admin', 'superadmin'], true);
}

//Redirect to login if not authenticated
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: auth/login.php');
        exit();
    }
}

//Redirect to dashboard if not admin
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('Location: dashboard.php?error=access_denied');
        exit();
    }
}

//Get current logged-in user info
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    require_once __DIR__ . '/../config/database.php';
    global $db;
    
    $user_query = $db->prepare("SELECT * FROM users WHERE id = ?");
    $user_query->execute([$_SESSION['user_id']]);
    return $user_query->fetch();
}
?>
