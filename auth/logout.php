<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/activity_logger.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Log logout before destroying the session
    logActivity($db, $user_id, 'User logged out.');

    // Mark as logged out
    $stmt = $db->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
    $stmt->execute([$user_id]);
}


session_unset();
session_destroy();

header('Location: ../index.php?success=You have been logged out successfully.');
exit();
