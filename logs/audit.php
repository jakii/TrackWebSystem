<?php
require_once '../config/config.php';
require_once '../includes/auth_check.php';
requireAuth();

require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// PDF support (TCPDF)
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recent_logs = [];
$userFilter = $_GET['user'] ?? '';
$dateFilter = $_GET['date'] ?? '';
$exportType = $_GET['export'] ?? null;

$sql = "SELECT a.id, u.username, a.message, a.timestamp 
        FROM activity_logs a 
        LEFT JOIN users u ON a.user_id = u.id";

$conditions = [];
$params = [];
$types = '';

if (!empty($userFilter)) {
    $conditions[] = "u.username LIKE ?";
    $params[] = '%' . $userFilter . '%';
    $types .= 's';
}

if (!empty($dateFilter)) {
    $conditions[] = "DATE(a.timestamp) = ?";
    $params[] = $dateFilter;
    $types .= 's';
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY a.timestamp DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$recent_logs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* ========= EXPORT TO EXCEL ========= */
if ($exportType === 'excel' && count($recent_logs) > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Activity Logs');

    // Header Row
    $headers = ['#', 'User', 'Message', 'Timestamp'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col.'1', $header);
        $col++;
    }

    // Data Rows
    $row = 2;
    foreach ($recent_logs as $index => $log) {
        $sheet->setCellValue('A'.$row, $index + 1);
        $sheet->setCellValue('B'.$row, $log['username'] ?? 'Unknown');
        $sheet->setCellValue('C'.$row, $log['message']);
        $sheet->setCellValue('D'.$row, date('M d, Y g:i A', strtotime($log['timestamp'])));
        $row++;
    }

    // Auto size columns
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="activity_logs.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

/* ========= EXPORT TO PDF ========= */
if ($exportType === 'pdf' && count($recent_logs) > 0) {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h3>Activity Logs</h3>
    <table border="1" cellpadding="5">
        <thead>
            <tr style="font-weight:bold; background-color:#f0f0f0;">
                <th width="40">#</th>
                <th width="100">User</th>
                <th width="240">Message</th>
                <th width="120">Timestamp</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($recent_logs as $index => $log) {
        $html .= "<tr>
            <td>" . ($index + 1) . "</td>
            <td>" . htmlspecialchars($log['username'] ?? 'Unknown') . "</td>
            <td>" . htmlspecialchars($log['message']) . "</td>
            <td>" . date('M d, Y g:i A', strtotime($log['timestamp'])) . "</td>
        </tr>";
    }

    $html .= '</tbody></table>';

    $pdf->writeHTML($html);
    $pdf->Output('activity_logs.pdf', 'D');
    exit;
}
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
  <div>
    <h4 class="mb-1">Activity Logs</h4>
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item active">Audit Trail</li>
    </ol>
  </div>

  <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
    <i class="fas fa-arrow-left me-2"></i> Go Back
  </button>
</div>

<hr class="mt-3 mb-4">

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="user" class="form-control" placeholder="Filter by username..." value="<?= htmlspecialchars($userFilter) ?>">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($dateFilter) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn w-100" style="background-color: #004F80; color: white;">Apply Filters</button>
                </div>
                <div class="col-md-3 text-end">
                    <?php if (count($recent_logs) > 0): ?>
                        <div class="btn-group w-100">
                            <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'excel'])) ?>" class="btn btn-success w-50">Export Excel</a>
                            <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'pdf'])) ?>" class="btn btn-danger w-50">Export PDF</a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card mb-4 shadow rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-list me-2" style="color:#004F80;"></i> Recent Activities
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($recent_logs)): ?>
                <p class="text-muted">No activity logs available.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Message</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_logs as $index => $log): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($log['username'] ?? 'Unknown') ?></td>
                                    <td><?= htmlspecialchars($log['message']) ?></td>
                                    <td><?= date('M d, Y g:i A', strtotime($log['timestamp'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
