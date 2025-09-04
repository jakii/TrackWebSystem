<?php
require_once '../config/database.php';

$labels = [];
$views = [];
$downloads = [];

// last 7 days
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('D', strtotime($day));

    // views
    $stmt = $db->prepare("SELECT COUNT(*) FROM document_activity WHERE activity_type = 'view' AND DATE(created_at) = ?");
    $stmt->execute([$day]);
    $views[] = (int)$stmt->fetchColumn();

    // downloads
    $stmt = $db->prepare("SELECT COUNT(*) FROM document_activity WHERE activity_type = 'download' AND DATE(created_at) = ?");
    $stmt->execute([$day]);
    $downloads[] = (int)$stmt->fetchColumn();
}

echo json_encode([
    "labels" => $labels,
    "views" => $views,
    "downloads" => $downloads
]);
