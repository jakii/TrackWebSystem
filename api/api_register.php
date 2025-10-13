<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/activity_logger.php';

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

    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = 'Password must be at least 8 characters long and include an uppercase letter, 
                  a lowercase letter, a number, and a special character.';
    } else {
        $check_user_query = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_user_query->execute([$username, $email]);

        if ($check_user_query->fetch()) {
            $error = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $status = 'pending';
            $role = 'user';

            $insert_user_query = $db->prepare("
                INSERT INTO users (username, email, password, full_name, role, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            if ($insert_user_query->execute([$username, $email, $hashed_password, $full_name, $role, $status])) {
                $success = 'Registration successful! Your account is pending admin approval.';
                $user_id = $db->lastInsertId();
                logActivity($db, $user_id, 'User registered.');
                
                $username = $email = $full_name = '';
                
                header('Location: ../auth/login.php?success=Your account is pending admin approval.');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
