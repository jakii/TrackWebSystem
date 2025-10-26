<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/storage_functions.php';
requireAuth();
require_once __DIR__ . '/includes/header.php';

$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

// --- External storage info ---
$external = getExternalStorageInfo();
$external_used = $external['used'];
$external_total = $external['total'];

//60% threshold
$threshold_percent = 60;
$auto_limit = ($external_total * $threshold_percent) / 100;

// Get admin-defined limit
$db_limit = getStorageLimit($db);

// Use whichever is smaller — respects admin limit but not exceed threshold
$limit = ($db_limit && $db_limit < $auto_limit) ? $db_limit : $auto_limit;

// --- Compute other values ---
$total_used = getTotalStorageUsed($db);
$available = $limit - $total_used;
$percent_total = ($limit > 0) ? ($total_used / $limit) * 100 : 0;
$percent_available = ($limit > 0) ? ($available / $limit) * 100 : 100;

// --- External stats ---
$external_available = $external_total - $external_used;
$external_percent = ($external_total > 0) ? ($external_used / $external_total) * 100 : 0;
$external_percent_available = ($external_total > 0) ? ($external_available / $external_total) * 100 : 0;

// --- User-specific usage ---
$user_used = $db->query("
    SELECT COALESCE(SUM(file_size), 0) AS used
    FROM documents
    WHERE uploaded_by = $user_id AND (is_deleted = 0 OR is_deleted IS NULL)
")->fetch(PDO::FETCH_ASSOC)['used'] ?? 0;

$user_available = $limit - $user_used;
$percent_user = ($limit > 0) ? ($user_used / $limit) * 100 : 0;
$percent_user_available = ($limit > 0) ? ($user_available / $limit) * 100 : 100;

// --- Handle admin update for manual limit ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limit_gb']) && $is_admin) {
    $new_limit = (float)$_POST['limit_gb'] * 1024 * 1024 * 1024;

    if ($new_limit > $external_total) {
        echo "<div class='alert alert-danger mt-3 w-50 text-start'>
        The storage limit cannot exceed the total system storage.
        </div>";
        echo "<meta http-equiv='refresh' content='2'>";
    } else {
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'storage_limit'");
        $stmt->execute([$new_limit]);
        echo "<div class='alert alert-success mt-3 w-50 text-start'>Storage limit updated!</div>";
        echo "<meta http-equiv='refresh' content='2'>";
    }
}
?>
<link rel="stylesheet" href="assets/css/storage.css">
<div class="container py-4">
  <h2 class="mb-3" style="color: var(--primary);">
    <i class="fas fa-database me-2" style="color: var(--accent);"></i>
    Storage <?= $is_admin ? 'Overview' : 'Usage' ?>
  </h2>

  <?php if ($is_admin): ?>
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5>System Storage: <?= formatBytes($external_used) ?> / <?= formatBytes($external_total) ?></h5>
      <div class="progress" style="height: 15px;">
        <div class="progress-bar <?= $external_percent > 90 ? 'bg-danger' : ($external_percent > 70 ? 'bg-warning' : 'bg-info') ?>"
             role="progressbar"
             style="width: <?= min($external_percent, 100) ?>%"></div>
      </div>
      <small><?= round($external_percent_available, 2) ?>% available</small>
    </div>
  </div>
  <?php endif; ?>

  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5><?= $is_admin ? "Total Used" : "Your Usage" ?>:
          <?= formatBytes($is_admin ? $total_used : $user_used) ?> / <?= formatBytes($limit) ?></h5>
      <div class="progress" style="height: 15px;">
        <div class="progress-bar <?= ($is_admin ? $percent_total : $percent_user) > 90 ? 'bg-danger' : (($is_admin ? $percent_total : $percent_user) > 70 ? 'bg-warning' : 'bg-success') ?>"
             role="progressbar"
             style="width: <?= min($is_admin ? $percent_total : $percent_user, 100) ?>%"></div>
      </div>
      <small><?= round($is_admin ? $percent_available : $percent_user_available, 2) ?>% available</small>
    </div>
  </div>

<?php if ($is_admin): ?>
  <?php
    $users = $db->query("
        SELECT u.full_name, COALESCE(SUM(d.file_size), 0) AS used
        FROM users u
        LEFT JOIN documents d ON u.id = d.uploaded_by
        GROUP BY u.id
        ORDER BY used DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $files = $db->query("
        SELECT d.original_filename, d.file_size, d.uploaded_by, d.created_at, u.full_name as uploader_name
        FROM documents d
        LEFT JOIN users u ON d.uploaded_by = u.id
        WHERE d.is_deleted = 0 OR d.is_deleted IS NULL
        ORDER BY d.file_size DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $folders = [];
    if ($db->query("SHOW TABLES LIKE 'folders'")->rowCount() > 0) {
        $folders = $db->query("
            SELECT f.name, COALESCE(SUM(d.file_size),0) AS used
            FROM folders f
            LEFT JOIN documents d ON f.id = d.folder_id
            GROUP BY f.id
            ORDER BY used DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
  ?>
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3"><i class="fas fa-users me-2 text-primary"></i>Top Users by Storage Usage</h5>
      <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>User</th><th>Storage Used</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= formatBytes($u['used']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3"><i class="fas fa-file-alt me-2 text-success"></i>Top Documents by Size</h5>
      <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>File Name</th><th>Size</th><th>Uploaded By</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($files as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['original_filename']) ?></td>
            <td><?= formatBytes($f['file_size']) ?></td>
            <td><?= htmlspecialchars($f['uploader_name']) ?></td>
            <td><?= date("M d, Y h:i A", strtotime($f['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if (!empty($folders)): ?>
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3"><i class="fas fa-folder-open me-2 text-warning"></i>Top Folders by Storage Usage</h5>
      <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>Folder</th><th>Used</th></tr></thead>
        <tbody>
          <?php foreach ($folders as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['name']) ?></td>
            <td><?= formatBytes($f['used']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <hr class="my-4">
  <h5><i class="fas fa-sliders-h me-2 text-secondary"></i> Update Global Storage Limit</h5>
  <form method="POST" class="mb-4">
    <div class="row g-2 align-items-center">
      <div class="col-auto"><input type="number" name="limit_gb" class="form-control" placeholder="Enter GB" min="1" required></div>
      <div class="col-auto"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Update Limit</button></div>
    </div>
  </form>

<?php else: ?>
  <?php
    $user_files = $db->query("
        SELECT original_filename, file_size, created_at
        FROM documents
        WHERE uploaded_by = $user_id AND (is_deleted = 0 OR is_deleted IS NULL)
        ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Your Uploaded Files</h5>
      <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>File Name</th><th>Size</th><th>Date Uploaded</th></tr></thead>
        <tbody>
          <?php if (!empty($user_files)): ?>
            <?php foreach ($user_files as $f): ?>
              <tr>
                <td><?= htmlspecialchars($f['original_filename']) ?></td>
                <td><?= formatBytes($f['file_size']) ?></td>
                <td><?= date("M d, Y h:i A", strtotime($f['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="3" class="text-center text-muted">No uploaded files yet</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
</div>