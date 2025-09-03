<?php
function logActivity($db, $user_id, $message) {
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);
}
