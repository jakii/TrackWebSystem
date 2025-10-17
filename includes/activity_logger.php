<?php
function logActivity($db, $user_id, $action) {
    if (empty($user_id) || empty($action)) {
        error_log("logActivity skipped — missing user_id or action. User ID: " . $user_id . ", Action: " . $action);
        return false;
    }

    try {
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $action]);
        return true;
    } catch (PDOException $e) {
        error_log("logActivity error: " . $e->getMessage());
        return false;
    }
}

function logDocumentActivity($document_id, $user_id, $type) {
    global $db;

    if (empty($user_id) || empty($document_id) || empty($type)) {
        error_log("logDocumentActivity skipped — missing required parameters. User ID: " . $user_id . ", Document ID: " . $document_id . ", Type: " . $type);
        return false;
    }

    try {
        $stmt = $db->prepare("INSERT INTO document_activity (document_id, user_id, activity_type, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$document_id, $user_id, $type]);
        return true;
    } catch (PDOException $e) {
        error_log("logDocumentActivity error: " . $e->getMessage());
        return false;
    }
}
?>