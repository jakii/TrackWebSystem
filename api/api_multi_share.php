<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

// Initialize messages
$error = '';
$success = '';

// --- Handle POST: Share Document ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['share_document'])) {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        $document_ids = array_filter(array_map('intval', $_POST['document_ids'] ?? []));
        $usernames = array_filter(array_map('trim', explode(',', $_POST['share_with'] ?? '')));
        $permission = in_array($_POST['permission'] ?? 'view', ['view','download']) ? $_POST['permission'] : 'view';

        $success_msgs = [];
        $failed_msgs = [];

        foreach ($document_ids as $doc_id) {
            // Fetch document
            $doc_id = (int)$doc_id;
            $document = $db->query("SELECT * FROM documents WHERE id=$doc_id")->fetch();
            
            if (!$document || ($document['uploaded_by'] != $_SESSION['user_id'] && !isAdmin() && $document['visibility'] != 'public')) {
                $failed_msgs[] = "Document ID $doc_id not found or access denied.";
                continue;
            }

            foreach ($usernames as $username) {
                $username_safe = $db->quote($username);
                $share_user = $db->query("SELECT id, username FROM users WHERE username=$username_safe OR email=$username_safe")->fetch();

                if (!$share_user) {
                    $failed_msgs[] = "$username (not found)";
                    continue;
                }
                if ($share_user['id'] == $_SESSION['user_id']) {
                    $failed_msgs[] = "$username (cannot share with yourself)";
                    continue;
                }

                $exists = $db->query("SELECT id FROM document_shares WHERE document_id=$doc_id AND shared_with={$share_user['id']}")->fetch();
                if ($exists) {
                    $failed_msgs[] = "Already shared Document ID $doc_id with {$share_user['username']}";
                    continue;
                }

                $insert = $db->query("INSERT INTO document_shares (document_id, shared_with, shared_by, permission) 
                                      VALUES ($doc_id, {$share_user['id']}, {$_SESSION['user_id']}, '$permission')");
                if ($insert) {
                    $success_msgs[] = "Document ID $doc_id shared with {$share_user['username']} ({$permission})";
                    logActivity($db, $_SESSION['user_id'], "Shared document '{$document['title']}' (ID: $doc_id) with {$share_user['username']} permission: $permission");
                } else {
                    $failed_msgs[] = "Failed to share Document ID $doc_id with {$share_user['username']}";
                }
            }
        }

        $success = $success_msgs ? implode('<br>', $success_msgs) : '';
        $error = $failed_msgs ? implode('<br>', $failed_msgs) : '';
    }
}

// --- Handle POST: Unshare Document ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unshare_document'])) {
    if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $share_id = (int)($_POST['share_id'] ?? 0);
        $delete = $db->query("DELETE FROM document_shares WHERE id=$share_id AND shared_by={$_SESSION['user_id']}");
        if ($delete) {
            logActivity($db, $_SESSION['user_id'], "Removed sharing (ID: $share_id)");
            $success = 'Sharing removed successfully.';
        } else {
            $error = 'Failed to remove sharing.';
        }
    } else {
        $error = 'Invalid CSRF token.';
    }
}

// --- Fetch all documents for the current user ---
$documents = $db->query("
    SELECT d.*, c.name AS category_name, c.color AS category_color
    FROM documents d
    LEFT JOIN categories c ON d.category_id = c.id
    WHERE d.uploaded_by = {$_SESSION['user_id']}
    ORDER BY d.created_at DESC
")->fetchAll();

// --- Fetch current shares ---
$current_shares = $db->query("
    SELECT ds.*, u.username, u.full_name, d.title AS document_title
    FROM document_shares ds
    JOIN users u ON ds.shared_with = u.id
    JOIN documents d ON ds.document_id = d.id
    WHERE ds.shared_by = {$_SESSION['user_id']}
    ORDER BY ds.created_at DESC
")->fetchAll();
