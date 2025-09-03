<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$document_id = (int)($_GET['id'] ?? 0);

if (!$document_id) {
    echo "<script>window.location.href='../dashboard.php?error=" . urlencode('Invalid document ID.') . "';</script>";
    exit();
}

// get document details
$doc_query = $db->prepare("
    SELECT d.*, c.name as category_name 
    FROM documents d 
    LEFT JOIN categories c ON d.category_id = c.id 
    WHERE d.id = ?
");
$doc_query->execute([$document_id]);
$document = $doc_query->fetch();

// check access rights
if (
    !$document ||
    (
        $document['uploaded_by'] != $_SESSION['user_id'] && 
        !isAdmin() && 
        $document['visibility'] != 'public'
    )
) {
    echo "<script>window.location.href='../dashboard.php?error=" . urlencode('Document not found or access denied.') . "';</script>";
    exit();
}

// handle share form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['share_document'])) {
    $share_with_username = trim($_POST['share_with'] ?? '');
    $permission = $_POST['permission'] ?? 'view';

    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        if (empty($share_with_username)) {
            $error = 'Please enter a username to share with.';
        } else {
            // find user by username or email
            $user_lookup_query = $db->prepare("SELECT id, username, full_name FROM users WHERE username = ? OR email = ?");
            $user_lookup_query->execute([$share_with_username, $share_with_username]);
            $share_user = $user_lookup_query->fetch();

            if (!$share_user) {
                $error = 'User not found.';
            } elseif ($share_user['id'] == $_SESSION['user_id']) {
                $error = 'You cannot share a document with yourself.';
            } else {
                // check if already shared
                $existing_share_query = $db->prepare("SELECT id FROM document_shares WHERE document_id = ? AND shared_with = ?");
                $existing_share_query->execute([$document_id, $share_user['id']]);

                if ($existing_share_query->fetch()) {
                    $error = 'Document already shared with this user.';
                } else {
                    // create share
                    $insert_share_query = $db->prepare("
                        INSERT INTO document_shares (document_id, shared_with, shared_by, permission) 
                        VALUES (?, ?, ?, ?)
                    ");
                    if ($insert_share_query->execute([$document_id, $share_user['id'], $_SESSION['user_id'], $permission])) {
                        // Log activity
                        $user_id = $_SESSION['user_id'];
                        $doc_title = $document['title'] ?? 'Unknown';
                        $shared_to = $share_user['full_name'] ?? $share_user['username'];
                        logActivity($db, $user_id, "Shared document: {$doc_title} (ID: {$document_id}) to {$shared_to} with permission: {$permission}");
                        echo "<script>window.location.href='../documents/share.php?id={$document_id}&success=" . urlencode('Document shared successfully with ' . htmlspecialchars($share_user['full_name'])) . "';</script>";
                        exit();
                    } else {
                        $error = 'Failed to share document.';
                    }
                }
            }
        }
    } else {
        $error = 'Invalid security token.';
    }
}

// handle unshare
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unshare_document'])) {
    $share_id = (int)$_POST['share_id'];

    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $delete_share_query = $db->prepare("DELETE FROM document_shares WHERE id = ? AND shared_by = ?");
        if ($delete_share_query->execute([$share_id, $_SESSION['user_id']])) {
            // Log activity
            $user_id = $_SESSION['user_id'];
            $doc_title = $document['title'] ?? 'Unknown';
            logActivity($db, $user_id, "Unshared document: {$doc_title} (ID: {$document_id})");
            echo "<script>window.location.href='../documents/share.php?id={$document_id}&success=" . urlencode('Document sharing removed successfully.') . "';</script>";
            exit();
        } else {
            $error = 'Failed to remove sharing.';
        }
    } else {
        $error = 'Invalid security token.';
    }
}

// get shares
$current_shares_query = $db->prepare("
    SELECT ds.*, u.username, u.full_name, u.email 
    FROM document_shares ds 
    JOIN users u ON ds.shared_with = u.id 
    WHERE ds.document_id = ? AND ds.shared_by = ?
    ORDER BY ds.created_at DESC
");
$current_shares_query->execute([$document_id, $_SESSION['user_id']]);
$current_shares = $current_shares_query->fetchAll();
?>
