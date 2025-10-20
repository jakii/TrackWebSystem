<?php
function getTotalStorageUsed($db) {
    $result = $db->query("SELECT SUM(file_size) AS total_used FROM documents")->fetch(PDO::FETCH_ASSOC);
    return $result['total_used'] ?? 0;
}

function getStorageLimit($db) {
    $result = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'storage_limit'")->fetch(PDO::FETCH_ASSOC);
    return (float)($result['setting_value'] ?? 0);
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getSystemRootPath() {
    return strtoupper(substr(PHP_OS_FAMILY, 0, 3)) === 'WIN' ? 'C:/' : '/';
}

function getExternalStorageInfo($path = null) {
    if ($path === null) $path = getSystemRootPath();
    if (!is_dir($path)) return ['used' => 0, 'total' => 0, 'percent' => 0];
    $total = @disk_total_space($path);
    $free  = @disk_free_space($path);
    if ($total === false || $free === false) return ['used' => 0, 'total' => 0, 'percent' => 0];
    $used = $total - $free;
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
    $result = $db->query("SELECT COALESCE(SUM(file_size), 0) AS used FROM documents WHERE uploaded_by = $user_id AND (is_deleted = 0 OR is_deleted IS NULL)")->fetch(PDO::FETCH_ASSOC);
    $user_used = $result['used'] ?? 0;
    $limit = getStorageLimit($db);
    return max($limit - $user_used, 0);
}
?>
