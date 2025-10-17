<?php
// Auto-detect environment
$is_localhost = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) 
                || ($_SERVER['SERVER_ADDR'] === '127.0.0.1') 
                || ($_SERVER['SERVER_NAME'] === 'localhost')
                || (isset($_SERVER['DEV_ENVIRONMENT']) && $_SERVER['DEV_ENVIRONMENT'] === 'true');

// Output buffering for both environments
if (!headers_sent() && !ob_get_level()) {
    ob_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'database');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('APP_NAME', 'Tvet Record and Archival Control Kiosk');

// Dynamic BASE_URL based on environment
if ($is_localhost) {
    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/TrackWeb/');
    define('IS_HTTPS', false);
} else {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/TrackWeb/');
    define('IS_HTTPS', true);
}

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../documents/uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', [
    'pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png',
    'gif', 'zip', 'rar', 'xlsx', 'xls', 'ppt', 'pptx'
]);

// Session & Security Configuration
define('SESSION_TIMEOUT', 3600);
define('CSRF_TOKEN_NAME', 'csrf_token');
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Manila');

// Dynamic Session Settings for Localhost vs VPS
if ($is_localhost) {
    // LOCALHOST SETTINGS - Less strict for development
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_samesite', 'Lax');
    error_log("Application running in LOCALHOST mode");
} else {
    // VPS/PRODUCTION SETTINGS - Strict security
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    error_log("Application running in PRODUCTION mode");
}

// Common Session Settings for Both Environments
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);
ini_set('session.cookie_lifetime', 0);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF Token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validate CSRF Token
 */
function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Logout function
 */
function logout() {
    session_unset();
    session_destroy();
    redirect(BASE_URL . 'auth/login.php');
}

/**
 * Check session timeout
 */
function checkSessionTimeout() {
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
        logout();
    } else {
        $_SESSION['login_time'] = time();
    }
}

/**
 * Format file size to human readable
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Get file icon based on extension
 */
function getFileIcon($extension) {
    $icons = [
        'pdf' => 'fas fa-file-pdf text-danger',
        'doc' => 'fas fa-file-word text-primary',
        'docx' => 'fas fa-file-word text-primary',
        'txt' => 'fas fa-file-alt text-secondary',
        'jpg' => 'fas fa-file-image text-info',
        'jpeg' => 'fas fa-file-image text-info',
        'png' => 'fas fa-file-image text-info',
        'gif' => 'fas fa-file-image text-info',
        'zip' => 'fas fa-file-archive text-warning',
        'rar' => 'fas fa-file-archive text-warning',
        'xlsx' => 'fas fa-file-excel text-success',
        'xls' => 'fas fa-file-excel text-success',
        'ppt' => 'fas fa-file-powerpoint text-danger',
        'pptx' => 'fas fa-file-powerpoint text-danger'
    ];
    
    return $icons[strtolower($extension)] ?? 'fas fa-file text-muted';
}

/**
 * Universal redirect function for both localhost and VPS
 */
function redirect($url) {
    // Clean all output buffers
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // For both localhost and VPS
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit();
    } else {
        // Fallback for both environments
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Redirecting...</title>
            <meta http-equiv="refresh" content="0;url=' . $url . '">
        </head>
        <body>
            <script>window.location.href="' . $url . '";</script>
            <p>If you are not redirected, <a href="' . $url . '">click here</a>.</p>
        </body>
        </html>';
        exit();
    }
}

/**
 * Check if running on localhost
 */
function isLocalhost() {
    return (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) 
           || ($_SERVER['SERVER_ADDR'] === '127.0.0.1') 
           || ($_SERVER['SERVER_NAME'] === 'localhost');
}

// Initialize CSRF token if not exists
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    generateCSRFToken();
}
?>