<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/storage_functions.php';
requireAuth();
require_once __DIR__ . '/includes/header.php';

$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

// === GLOBAL STORAGE INFO ===
$total_used = getTotalStorageUsed($db);
$limit = getStorageLimit($db);
$percent_total = ($limit > 0) ? ($total_used / $limit) * 100 : 0;

// === USER STORAGE INFO ===
$stmt = $db->prepare("
    SELECT COALESCE(SUM(file_size), 0) AS used
    FROM documents
    WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
");
$stmt->execute([$user_id]);
$user_used = $stmt->fetchColumn();
$percent_user = ($limit > 0) ? ($user_used / $limit) * 100 : 0;

// Handle storage limit update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limit_gb']) && $is_admin) {
    $new_limit = (float)$_POST['limit_gb'] * 1024 * 1024 * 1024;
    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'storage_limit'");
    $stmt->execute([$new_limit]);
    echo "<div class='alert alert-success mt-3'>Storage limit updated successfully!</div>";
    echo "<meta http-equiv='refresh' content='1'>";
}
?>
<link rel="stylesheet" href="assets/css/storage.css">
<div class="container py-4">
  <h2 class="mb-3" style="color: var(--primary);">
    <i class="fas fa-database me-2" style="color: var(--accent);"></i>
    Storage <?= $is_admin ? 'Overview' : 'Usage' ?>
  </h2>

  <!-- STORAGE SUMMARY -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5><?= $is_admin ? "Total Used" : "Your Usage" ?>: 
          <?= formatBytes($is_admin ? $total_used : $user_used) ?> / <?= formatBytes($limit) ?>
      </h5>
      <div class="progress" style="height: 15px;">
        <div class="progress-bar <?= ($is_admin ? $percent_total : $percent_user) > 90 ? 'bg-danger' : (($is_admin ? $percent_total : $percent_user) > 70 ? 'bg-warning' : 'bg-success') ?>"
             role="progressbar"
             style="width: <?= min($is_admin ? $percent_total : $percent_user, 100) ?>%">
        </div>
      </div>
      <small>
        <?= round($is_admin ? $percent_total : $percent_user, 2) ?>% <?= $is_admin ? 'used system-wide' : 'of total system storage' ?>
      </small>
    </div>
  </div>

<?php if ($is_admin): ?>
  <!-- === TOP USERS === -->
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

  <!-- TOP USERS -->
<!-- TOP USERS -->
<!-- TOP USERS -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="mb-3">
      <i class="fas fa-users me-2 text-primary"></i>Top Users by Storage Usage
    </h5>
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th><i class="fas fa-user me-1 text-primary"></i>User</th>
          <th><i class="fas fa-database me-1 text-secondary"></i>Storage Used</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <?php 
            $initial = strtoupper(substr($u['full_name'], 0, 1)); 
          ?>
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div style="width: 34px; height: 34px; border-radius: 50%;
                            background: linear-gradient(135deg, #004F80, #0073b6);
                            display: flex; align-items: center; justify-content: center;
                            color: white; font-weight: 600; font-size: 0.9rem; margin-right: 10px;">
                  <?= $initial ?>
                </div>
                <span><?= htmlspecialchars($u['full_name']) ?></span>
              </div>
            </td>
            <td><?= formatBytes($u['used']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- TOP FILES -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="mb-3">
      <i class="fas fa-file-alt me-2 text-success"></i>Top Documents by Size
    </h5>
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th><i class="fas fa-file me-1 text-success"></i>File Name</th>
          <th><i class="fas fa-weight-hanging me-1 text-secondary"></i>Size</th>
          <th><i class="fas fa-user me-1 text-primary"></i>Uploaded By</th>
          <th><i class="fas fa-calendar-alt me-1 text-muted"></i>Date Uploaded</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($files as $f): ?>
          <tr>
            <td>
              <i class="<?= getFileIcon(pathinfo($f['original_filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
              <?= htmlspecialchars($f['original_filename']) ?>
            </td>
            <td><?= formatBytes($f['file_size']) ?></td>
            <td><?= htmlspecialchars($f['uploader_name']) ?></td>
            <td><?= date("M d, Y h:i A", strtotime($f['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- TOP FOLDERS -->
<?php if (!empty($folders)): ?>
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3">
        <i class="fas fa-folder-open me-2 text-warning"></i>
        Top Folders by Storage Usage
      </h5>
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Folder</th>
            <th>Used</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($folders as $f): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <i class="fas fa-folder me-2" 
                     style="color: <?= htmlspecialchars($f['color'] ?? '#f4c542') ?>;"></i>
                  <span><?= htmlspecialchars($f['name']) ?></span>
                </div>
              </td>
              <td><?= formatBytes($f['used']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>


<!-- UPDATE STORAGE LIMIT -->
<hr class="my-4">
<h5><i class="fas fa-sliders-h me-2 text-secondary"></i> Update Global Storage Limit</h5>
<form method="POST" class="mb-4">
  <div class="row g-2 align-items-center">
    <div class="col-auto">
      <input type="number" name="limit_gb" class="form-control" placeholder="Enter GB" min="1" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary" type="submit">
        <i class="fas fa-save me-1"></i> Update Limit
      </button>
    </div>
  </div>
</form>


<?php else: ?>
  <!-- USER FILES -->
  <?php
    $user_files = $db->prepare("
        SELECT original_filename, file_size, created_at
        FROM documents
        WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
        ORDER BY created_at DESC
    ");
    $user_files->execute([$user_id]);
    $user_files = $user_files->fetchAll(PDO::FETCH_ASSOC);
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
