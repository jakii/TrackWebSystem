<?php
require_once 'includes/auth_check.php';
require_once 'config/database.php';
requireAuth();

date_default_timezone_set('Asia/Manila');

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// filters
$userFilter = $_GET['user'] ?? '';
$dateFilter = $_GET['date'] ?? '';
$exportType = $_GET['export'] ?? null;

// query
$query = "
    SELECT r.id, r.title, r.created_at, u.full_name AS uploaded_by
    FROM reports r
    JOIN users u ON r.uploaded_by = u.id
    WHERE 1=1
";
$params = [];

if (!empty($userFilter)) {
    $query .= " AND u.username LIKE :user";
    $params[':user'] = "%$userFilter%";
}
if (!empty($dateFilter)) {
    $query .= " AND DATE(CONVERT_TZ(r.created_at, '+00:00')) = :date";
    $params[':date'] = $dateFilter;
}

$query .= " ORDER BY r.created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Excel export
if ($exportType === 'excel' && count($reports) > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reports');

    $headers = ['#', 'Title', 'Uploaded By', 'Date Uploaded'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col.'1', $header);
        $col++;
    }

    $row = 2;
    foreach ($reports as $index => $r) {
        $phTime = new DateTime($r['created_at'], new DateTimeZone('UTC'));
        $phTime->setTimezone(new DateTimeZone('Asia/Manila'));

        $sheet->setCellValue('A'.$row, $index + 1);
        $sheet->setCellValue('B'.$row, $r['title']);
        $sheet->setCellValue('C'.$row, $r['uploaded_by']);
        $sheet->setCellValue('D'.$row, $phTime->format('M d, Y h:i A'));
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="reports.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// PDF export
if ($exportType === 'pdf' && count($reports) > 0) {
    require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h3>Reports List</h3><table border="1" cellpadding="5">
        <thead><tr><th>#</th><th>Title</th><th>Uploaded By</th><th>Date Uploaded</th></tr></thead><tbody>';

    foreach ($reports as $index => $r) {
        $phTime = new DateTime($r['created_at'], new DateTimeZone('UTC'));
        $phTime->setTimezone(new DateTimeZone('Asia/Manila'));
        $html .= "<tr>
            <td>" . ($index + 1) . "</td>
            <td>" . htmlspecialchars($r['title']) . "</td>
            <td>" . htmlspecialchars($r['uploaded_by']) . "</td>
            <td>" . $phTime->format('M d, Y h:i A') . "</td>
        </tr>";
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('reports.pdf', 'D');
    exit;
}
?>


<?php include 'includes/header.php'; ?>
<div class="container mt-4">

  <!-- Filters -->
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
          <?php if (count($reports) > 0): ?>
            <div class="btn-group w-100">
              <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'excel'])) ?>" class="btn btn-success w-50">Export Excel</a>
              <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'pdf'])) ?>" class="btn btn-danger w-50">Export PDF</a>
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Reports Table -->
  <div class="card shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
      <h4 class="mb-0">
        <i class="fas fa-file-alt me-2" style="color:#004F80;"></i> Weekly Uploaded Reports
      </h4>
      <a href="dashboard.php" class="btn btn-sm rounded-pill px-3" style="background-color: #2AB7CA; color: white; font-weight: 500;">Back</a>
    </div>

    <div class="card-body">
      <?php if (count($reports) > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Uploaded By</th>
                <th>Date Uploaded</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reports as $index => $report): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($report['title']) ?></td>
                  <td><?= htmlspecialchars($report['uploaded_by']) ?></td>
                  <td><?= date("M d, Y h:i A", strtotime($report['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">No reports found matching your filters.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
