<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT message, timestamp FROM activity_logs WHERE user_id = ? ORDER BY timestamp DESC LIMIT 20");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();
?>
<div class="container fade-in delay-1">
  <div class="row">
    <div class="col-12">
      <h1><i class="fas fa-history me-2" style="color: #2AB7CA;"></i> Recent Activity</h1>
      <hr>
    </div>
  </div>
  <div class="card shadow rounded-4 border-0 mt-3">
    <div class="card-header bg-white border-bottom rounded-top-4">
      <h5 class="mb-0"><i class="fas fa-list me-2" style="color: #2AB7CA;"></i> Your Recent Actions</h5>
    </div>
    <div class="card-body">
      <?php if (empty($logs)): ?>
        <div class="text-center py-5">
          <i class="fas fa-history fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">No recent activity</h5>
          <p class="text-muted">Your actions will appear here.</p>
        </div>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($logs as $log): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><?php echo htmlspecialchars($log['message']); ?></span>
              <span class="text-muted small"><?php echo date('M d, Y g:i A', strtotime($log['timestamp'])); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
