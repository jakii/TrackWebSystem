<?php
function logActivity($db, $user_id, $message) {
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);
}
function logDocumentActivity($document_id, $user_id, $type) {
    global $db;
    $stmt = $db->prepare("INSERT INTO document_activity (document_id, user_id, activity_type) VALUES (?, ?, ?)");
    $stmt->execute([$document_id, $user_id, $type]);
}