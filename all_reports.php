<?php
require_once 'includes/auth_check.php';
require_once 'config/database.php';
requireAuth();

$report_query = $db->query("SELECT id, title, created_at, uploaded_by FROM reports ORDER BY created_at DESC");
$reports = $report_query->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <div class="card shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
      <h4 class="mb-0">
        <i class="fas fa-file-alt me-2" style="color:#004F80;"></i> All Reports
      </h4>
      <a href="dashboard.php" class="btn btn-sm btn-secondary">← Back</a>
    </div>
    <div class="card-body">
      <?php if (count($reports) > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Uploaded By</th>
                <th>Date Uploaded</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reports as $index => $report): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($report['title']) ?></td>
                  <td><?= htmlspecialchars($report['uploaded_by']) ?></td>
                  <td><?= date("M d, Y h:i A", strtotime($report['created_at'])) ?></td>
                  <td>
                    <a href="report_view.php?id=<?= $report['id'] ?>" class="btn btn-sm btn-primary">
                      View
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">No reports found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
