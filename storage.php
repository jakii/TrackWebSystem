<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT SUM(file_size) as total_size, COUNT(*) as total_files FROM documents WHERE uploaded_by = ? AND (is_deleted IS NULL OR is_deleted = 0)");
$stmt->execute([$user_id]);
$data = $stmt->fetch();
$total_size = $data['total_size'] ?? 0;
$total_files = $data['total_files'] ?? 0;
function formatBytes($bytes) {
  $units = ['B', 'KB', 'MB', 'GB', 'TB'];
  $i = 0;
  while ($bytes >= 1024 && $i < count($units) - 1) {
    $bytes /= 1024;
    $i++;
  }
  return round($bytes, 1) . ' ' . $units[$i];
}
?>
<div class="container fade-in delay-1">
  <div class="row">
    <div class="col-12">
      <h2><i class="fas fa-database me-2" style="color: #004f80;"></i> Storage Usage</h2>
      <hr>
    </div>
  </div>
  <div class="card shadow rounded-4 border-0 mt-3">
    <div class="card-header bg-white border-bottom rounded-top-4">
      <h5 class="mb-0"><i class="fas fa-chart-bar me-2" style="color: #004f80;"></i> Your Storage Details</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <h6>Total Files</h6>
            <div class="fs-4 fw-bold"><?php echo number_format($total_files); ?></div>
          </div>
          <div class="mb-3">
            <h6>Total Storage Used</h6>
            <div class="fs-4 fw-bold"><?php echo formatBytes($total_size); ?></div>
          </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center">
          <i class="fas fa-database fa-5x" style="color: #004f80;"></i>
        </div>
      </div>
    </div>
  </div>
</div>
