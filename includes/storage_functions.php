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
function getExternalStorageInfo($path = 'C:/') {
    if (!is_dir($path)) return ['used' => 0, 'total' => 0, 'percent' => 0];

    $total = disk_total_space($path);
    $free  = disk_free_space($path);
    $used  = $total - $free;
    $percent = ($total > 0) ? ($used / $total) * 100 : 0;

    return [
        'used' => $used,
        'total' => $total,
        'percent' => $percent
    ];
}

function getAvailableStorage($db) {
    $total_used = getTotalStorageUsed($db);
    $limit = getStorageLimit($db);
    return max($limit - $total_used, 0);
}

function getUserAvailableStorage($db, $user_id) {
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(file_size), 0) AS used
        FROM documents 
        WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
    ");
    $stmt->execute([$user_id]);
    $user_used = $stmt->fetchColumn();
    $limit = getStorageLimit($db);
    return max($limit - $user_used, 0);
}
