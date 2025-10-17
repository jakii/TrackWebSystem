<?php
/**
 * Log user activity
 */
function logActivity($db, $user_id, $action) {
    // Validate parameters
    if (empty($user_id) || empty($action)) {
        error_log("logActivity skipped — missing user_id or action. User ID: " . $user_id . ", Action: " . $action);
        return false;
    }

    try {
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, NOW())");
        $result = $stmt->execute([$user_id, $action]);
        
        if (!$result) {
            error_log("logActivity execute failed for user_id: " . $user_id);
            return false;
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("logActivity PDO error: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("logActivity general error: " . $e->getMessage());
        return false;
    }
}

/**
 * Log document-specific activity
 */
function logDocumentActivity($document_id, $user_id, $type) {
    global $db;

    // Validate parameters
    if (empty($user_id) || empty($document_id) || empty($type)) {
        error_log("logDocumentActivity skipped — missing required parameters. User ID: " . $user_id . ", Document ID: " . $document_id . ", Type: " . $type);
        return false;
    }

    try {
        $stmt = $db->prepare("INSERT INTO document_activity (document_id, user_id, activity_type, created_at) VALUES (?, ?, ?, NOW())");
        $result = $stmt->execute([$document_id, $user_id, $type]);
        
        if (!$result) {
            error_log("logDocumentActivity execute failed for document_id: " . $document_id);
            return false;
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("logDocumentActivity PDO error: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("logDocumentActivity general error: " . $e->getMessage());
        return false;
    }
}

/**
 * Safe logging with environment detection
 */
function safeLogActivity($db, $user_id, $action) {
    // Only log if we have valid database connection and user_id
    if (!$db || empty($user_id)) {
        return false;
    }
    
    return logActivity($db, $user_id, $action);
}
?>