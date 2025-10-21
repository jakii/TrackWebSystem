<?php
require_once '../includes/auth_check.php';
requireAuth();

$filename = basename($_GET['file']);
$file_path = "/var/www/TrackWeb/backups/uploads/" . $filename;

if (!file_exists($file_path)) {
    http_response_code(404);
    exit("File not found.");
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit;
