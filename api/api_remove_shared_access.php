<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';

requireAuth();

header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid document ID']);
    exit;
}

$document_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

// ✅ Check if the document is actually shared with this user
$stmt = $db->prepare("SELECT * FROM document_shares WHERE document_id = ? AND shared_with = ?");
$stmt->execute([$document_id, $user_id]);
$share = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$share) {
    echo json_encode(['status' => 'error', 'message' => 'You do not have access to this shared document.']);
    exit;
}

// ✅ Remove the share record
$delete = $db->prepare("DELETE FROM document_shares WHERE document_id = ? AND shared_with = ?");
$delete->execute([$document_id, $user_id]);

echo json_encode(['status' => 'success', 'message' => 'Access removed successfully.']);
exit;
?>
