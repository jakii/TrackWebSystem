<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $document_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$document_id) {
        header("Location: ../dashboard.php?error=" . urlencode('Invalid document ID.'));
        exit();
    }

    if (isset($_POST['share_document'])) {
        $share_with_username = trim($_POST['share_with'] ?? '');
        $permission = $_POST['permission'] ?? 'view';

        if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
            if (!empty($share_with_username)) {
                $user_lookup_query = $db->prepare("SELECT id, username, full_name FROM users WHERE username = ? OR email = ?");
                $user_lookup_query->execute([$share_with_username, $share_with_username]);
                $share_user = $user_lookup_query->fetch();

                if ($share_user && $share_user['id'] != $_SESSION['user_id']) {
                    $existing_share_query = $db->prepare("SELECT id FROM document_shares WHERE document_id = ? AND shared_with = ?");
                    $existing_share_query->execute([$document_id, $share_user['id']]);

                    if (!$existing_share_query->fetch()) {
                        $insert_share_query = $db->prepare("INSERT INTO document_shares (document_id, shared_with, shared_by, permission) VALUES (?, ?, ?, ?)");
                        $insert_share_query->execute([$document_id, $share_user['id'], $_SESSION['user_id'], $permission]);
                        $user_id = $_SESSION['user_id'];
                        $doc_title = $document['title'] ?? 'Unknown';
                        $shared_to = $share_user['full_name'] ?? $share_user['username'];
                        logActivity($db, $user_id, "Shared document: {$doc_title} (ID: {$document_id}) to {$shared_to} with permission: {$permission}");
                    }
                }
            }
        }
        header("Location: share.php?id=$document_id");
        exit();
    }

    if (isset($_POST['unshare_document'])) {
        $share_id = (int)$_POST['share_id'];
        if (validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $delete_share_query = $db->prepare("DELETE FROM document_shares WHERE id = ? AND shared_by = ?");
            $delete_share_query->execute([$share_id, $_SESSION['user_id']]);
            $user_id = $_SESSION['user_id'];
            $doc_title = $document['title'] ?? 'Unknown';
            logActivity($db, $user_id, "Unshared document: {$doc_title} (ID: {$document_id})");
        }
        header("Location: share.php?id=$document_id");
        exit();
    }
}
