<?php
function logActivity($db, $user_id, $action) {
    if (empty($user_id)) {
        error_log("logActivity skipped — no user_id for action: $action");
        return;
    }

    $created_at = date('Y-m-d H:i:s');

    try {
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $action, $created_at]);
    } catch (PDOException $e) {
        error_log("logActivity error: " . $e->getMessage());
    }
}

function logDocumentActivity($document_id, $user_id, $type) {
    global $db;

    if (empty($user_id) || empty($document_id)) {
        error_log("logDocumentActivity skipped — missing IDs");
        return;
    }

    $created_at = date('Y-m-d H:i:s');

    try {
        $stmt = $db->prepare("INSERT INTO document_activity (document_id, user_id, activity_type, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$document_id, $user_id, $type, $created_at]);
    } catch (PDOException $e) {
        error_log("logDocumentActivity error: " . $e->getMessage());
    }
}
