<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAuth();

$sql = "
    SELECT YEARWEEK(created_at, 1) AS week_number,
           YEAR(created_at) AS year,
           WEEK(created_at, 1) AS week,
           COUNT(*) AS total_uploads
    FROM reports
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 WEEK)
    GROUP BY YEARWEEK(created_at, 1), YEAR(created_at), WEEK(created_at, 1)
    ORDER BY year ASC, week ASC
";
$stmt = $db->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
for ($i = 6; $i >= 0; $i--) {
    $weekDate = new DateTime();
    $weekDate->modify("-$i week");

    $year = $weekDate->format("o");
    $week = $weekDate->format("W");

    $weekDate->setISODate($year, $week, 1);
    $startOfWeek = $weekDate->format("M d");
    $weekDate->setISODate($year, $week, 7);
    $endOfWeek = $weekDate->format("M d, Y");

    $uploads = 0;
    foreach ($results as $row) {
        if ((int)$row['year'] === (int)$year && (int)$row['week'] === (int)$week) {
            $uploads = (int)$row['total_uploads'];
            break;
        }
    }

    $data[] = [
        'week' => "$startOfWeek – $endOfWeek",
        'uploads' => $uploads
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
