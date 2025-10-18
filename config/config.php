<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'database');
define('DB_USER', 'root');
define('DB_PASS', '');

define('APP_NAME', 'Tvet Record and Archival Control Kiosk');
define('BASE_URL', '/TrackWeb/');

define('UPLOAD_DIR', __DIR__ . '/../documents/uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', [
    'pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png',
    'gif', 'zip', 'rar', 'xlsx', 'xls', 'ppt', 'pptx'
]);

define('SESSION_TIMEOUT', 3600);
define('CSRF_TOKEN_NAME', 'csrf_token');

define('ITEMS_PER_PAGE', 10);

date_default_timezone_set('Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit();
}

function checkSessionTimeout() {
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
        logout();
    } else {
        $_SESSION['login_time'] = time();
    }
}

function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

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
?>
