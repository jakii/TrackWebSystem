<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$document_id = (int)($_GET['id'] ?? 0);

if (!$document_id) {
    header('Location: ../dashboard.php?error=Invalid document ID.');
    exit();
}

// Get document details
$stmt = $db->prepare("
    SELECT * FROM documents 
    WHERE id = ? AND (uploaded_by = ? OR is_public = 1)
");
$stmt->execute([$document_id, $_SESSION['user_id']]);
$document = $stmt->fetch();

if (!$document) {
    header('Location: ../dashboard.php?error=Document not found or access denied.');
    exit();
}

// Check if file exists
if (!file_exists($document['file_path'])) {
    header('Location: ../dashboard.php?error=File not found on server.');
    exit();
}

// Update download count
$stmt = $db->prepare("UPDATE documents SET download_count = download_count + 1 WHERE id = ?");
$stmt->execute([$document_id]);
// Log activity
$user_id = $_SESSION['user_id'];
$doc_title = $document['title'] ?? 'Unknown';
logActivity($db, $user_id, "Downloaded document: {$doc_title} (ID: {$document_id})");

// Set headers for file download
header('Content-Type: ' . $document['file_type']);
header('Content-Disposition: attachment; filename="' . $document['original_filename'] . '"');
header('Content-Length: ' . $document['file_size']);
header('Cache-Control: private, no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output file
readfile($document['file_path']);
exit();
?>
