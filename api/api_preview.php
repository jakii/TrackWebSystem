<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$document_id = (int)($_GET['id'] ?? 0);

// Redirects and exits before any output
if (!$document_id) {
    header('Location: ../dashboard.php?error=Invalid document ID.');
    exit();
}

$doc_query = $db->prepare("
    SELECT d.*, c.name AS category_name, c.color AS category_color, u.full_name AS uploader_name
    FROM documents d 
    LEFT JOIN categories c ON d.category_id = c.id 
    LEFT JOIN users u ON d.uploaded_by = u.id
    WHERE d.id = ? AND (d.uploaded_by = ? OR d.is_public = 1)
");
$doc_query->execute([$document_id, $_SESSION['user_id']]);
$document = $doc_query->fetch();

if (!$document) {
    header('Location: ../dashboard.php?error=Document not found or access denied.');
    exit();
}

if (!file_exists($document['file_path'])) {
    header('Location: ../dashboard.php?error=File not found on server.');
    exit();
}

// No output before this point
logActivity($db, $_SESSION['user_id'], 'Document previewed: ' . $document['title']);

$file_extension = strtolower(pathinfo($document['filename'], PATHINFO_EXTENSION));
$is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif']);
$is_pdf = $file_extension === 'pdf';
$is_word = in_array($file_extension, ['doc', 'docx']);
$is_excel = in_array($file_extension, ['xls', 'xlsx']);
$is_powerpoint = in_array($file_extension, ['ppt', 'pptx']);
$is_text = in_array($file_extension, ['txt']);
$is_archive = in_array($file_extension, ['zip', 'rar']);

// Build public URL for browser preview - FIXED
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . '://' . $host;

// Get relative path from document root
$docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
$fileRealPath = realpath($document['file_path']);
$relativePath = str_replace($docRoot, '', $fileRealPath);
$relativePath = str_replace('\\', '/', ltrim($relativePath, '/\\'));

$document['public_url'] = $baseUrl . '/' . $relativePath;