<?php
function getTotalStorageUsed($db) {
    $stmt = $db->query("SELECT SUM(file_size) AS total_used FROM documents");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_used'] ?? 0;
}

function getStorageLimit($db) {
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'storage_limit'");
    $stmt->execute();
    return (float)($stmt->fetch(PDO::FETCH_ASSOC)['setting_value'] ?? 0);
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
