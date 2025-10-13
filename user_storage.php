<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth_check.php';
requireAuth();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/storage_functions.php';

$user_id = $_SESSION['user_id'];

// === GLOBAL STORAGE LIMIT (shared for all) ===
$global_limit = getStorageLimit($db);
$total_used = getTotalStorageUsed($db);

// === USER-SPECIFIC STORAGE USED ===
$stmt = $db->prepare("
    SELECT COALESCE(SUM(file_size), 0) AS used
    FROM documents
    WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
");
$stmt->execute([$user_id]);
$user_used = $stmt->fetchColumn();

$percent_user = ($global_limit > 0) ? ($user_used / $global_limit) * 100 : 0;

// === USER’S FILE LIST ===
$files = $db->prepare("
    SELECT file_name, file_size, uploaded_at, folder_id
    FROM documents
    WHERE uploaded_by = ? AND (is_deleted = 0 OR is_deleted IS NULL)
    ORDER BY uploaded_at DESC
");
$files->execute([$user_id]);
$files = $files->fetchAll(PDO::FETCH_ASSOC);

// === OPTIONAL: PER-FOLDER USAGE (if folder table exists) ===
$folders = [];
if ($db->query("SHOW TABLES LIKE 'folders'")->rowCount() > 0) {
    $folders = $db->prepare("
        SELECT f.folder_name, COALESCE(SUM(d.file_size), 0) AS used
        FROM folders f
        LEFT JOIN documents d ON f.id = d.folder_id
        WHERE d.uploaded_by = ?
        GROUP BY f.id
        ORDER BY used DESC
    ");
    $folders->execute([$user_id]);
    $folders = $folders->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container py-4">
  <h2 class="mb-3" style="color: var(--primary);">
    <i class="fas fa-database me-2" style="color: var(--accent);"></i> My Storage
  </h2>

  <!-- PERSONAL STORAGE SUMMARY -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5>Used: <?= formatBytes($user_used) ?> / <?= formatBytes($global_limit) ?></h5>
      <div class="progress" style="height: 15px;">
        <div class="progress-bar <?= $percent_user > 90 ? 'bg-danger' : ($percent_user > 70 ? 'bg-warning' : 'bg-success') ?>"
             role="progressbar"
             style="width: <?= min($percent_user, 100) ?>%">
        </div>
      </div>
      <small><?= round($percent_user, 2) ?>% of total system storage</small>
    </div>
  </div>

  <!-- PER-FOLDER SUMMARY (optional) -->
  <?php if (!empty($folders)): ?>
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3">Your Folders</h5>
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
              <td><?= htmlspecialchars($f['folder_name']) ?></td>
              <td><?= formatBytes($f['used']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- USER’S FILES -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">My Uploaded Files</h5>
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Date Uploaded</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($files)): ?>
            <?php foreach ($files as $f): ?>
              <tr>
                <td><?= htmlspecialchars($f['file_name']) ?></td>
                <td><?= formatBytes($f['file_size']) ?></td>
                <td><?= date("M d, Y h:i A", strtotime($f['uploaded_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center text-muted">No uploaded files yet</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>