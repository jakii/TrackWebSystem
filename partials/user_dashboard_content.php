<?php
// ===== Document Stats =====
$stats = getDocumentStats($db, $user_id, $is_admin);

// Fetch stats depending on role
if ($is_admin) {
    $total_admin_docs = $stats['total'] ?? 0;
    $total_admin_size = $stats['total_size'] ?? 0;
    $recent_admin_documents = getRecentDocuments($db, $user_id, $is_admin, 15);
} else {
    $total_user_docs = $stats['total'] ?? 0;
    $total_user_size = $stats['total_size'] ?? 0;
    $recent_user_documents = getRecentDocuments($db, $user_id, $is_admin, 15);
}

// ===== Shared Documents =====
try {
    // Get the documents shared with this user
    $stmt = $db->prepare("
        SELECT d.*, 
               c.name AS category_name, 
               c.color AS category_color, 
               f.name AS folder_name,
               f.color AS folder_color,
               u.full_name AS owner_name, 
               ds.permission
        FROM documents d 
        LEFT JOIN categories c ON d.category_id = c.id
        LEFT JOIN folders f ON d.folder_id = f.id
        LEFT JOIN users u ON d.uploaded_by = u.id
        JOIN document_shares ds ON d.id = ds.document_id
        WHERE ds.shared_with = ?
        ORDER BY ds.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $shared_docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total shared documents
    $shared_count_stmt = $db->prepare("
        SELECT COUNT(*) FROM document_shares WHERE shared_with = ?
    ");
    $shared_count_stmt->execute([$user_id]);
    $shared_docs_count = (int) $shared_count_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Error fetching shared documents: " . $e->getMessage());
    $shared_docs = [];
    $shared_docs_count = 0;
}
?>

<!-- ===================== USER DASHBOARD ===================== -->

<div class="row g-4 mb-5">
    <?php
    $userStats = [
        [
            'title' => 'My Documents',
            'icon' => 'fa-file-alt',
            'value' => $total_user_docs,
            'desc' => 'Total uploaded documents'
        ],
        [
            'title' => 'My Storage Used',
            'icon' => 'fa-database',
            'value' => formatFileSize($total_user_size),
            'desc' => 'Space used by your files'
        ],
        [
            'title' => 'Shared with Me',
            'icon' => 'fa-share-alt',
            'value' => $shared_docs_count,
            'desc' => 'Files shared by others'
        ]
    ];

    foreach ($userStats as $stat): ?>
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card h-90">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle me-3">
                            <i class="fas <?= $stat['icon'] ?> fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-1"><?= $stat['title'] ?></h6>
                            <div class="stat-value"><?= htmlspecialchars($stat['value']); ?></div>
                            <small class="text-white-50"><?= $stat['desc'] ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<!-- ===================== RECENT UPLOADS ===================== -->

<div class="documents-table mt-4">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h5 class="mb-1 fw-bold">
          <i class="fas fa-folder-open me-2" style="color: var(--primary-color);"></i>
          My Recent Uploads
        </h5>
        <small class="text-muted">Recently added to your workspace</small>
      </div>
      <a href="documents/browse.php" class="btn btn-accent-custom btn-sm">
        <i class="fas fa-folder-open me-2"></i>Browse
      </a>
    </div>

    <div class="card-body p-0">
      <?php if (empty($recent_user_documents)): ?>
        <div class="empty-state">
          <i class="fas fa-file-upload"></i>
          <p>You haven’t uploaded any documents yet.</p>
          <a href="documents/upload.php" class="btn btn-accent-custom" style="">
            <i class="fas fa-upload me-2"></i>Upload Document
          </a>
        </div>
      <?php else: ?>
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Document</th>
              <th>Category</th>
              <th>Size</th>
              <th>Date</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_user_documents as $i => $doc): ?>
              <tr class="user-doc-row <?= $i >= 5 ? 'd-none' : '' ?>">
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="file-icon">
                      <i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?>" style="color: var(--primary-color);"></i>
                    </div>
                    <div>
                      <div class="fw-semibold text-dark"><?= htmlspecialchars($doc['title']); ?></div>
                      <small class="text-muted">
                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['username']); ?>
                      </small>
                    </div>
                  </div>
                </td>
                <td>
                  <?php if ($doc['category_name']): ?>
                    <span class="badge badge-custom" style="background-color: <?= $doc['category_color'] ?>; color: white;">
                      <?= htmlspecialchars($doc['category_name']); ?>
                    </span>
                  <?php else: ?>
                    <span class="badge badge-custom" style="background-color: #6B7280; color: white;">Uncategorized</span>
                  <?php endif; ?>
                </td>
                <td class="text-muted"><?= formatFileSize($doc['file_size']); ?></td>
                <td class="text-muted">
                  <i class="fas fa-calendar-alt me-1"></i>
                  <?= date('M j, Y g:i A', strtotime($doc['created_at'])); ?>
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn-action" data-bs-toggle="dropdown">
                      <i class="fas fa-ellipsis-v text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="documents/preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i>Preview</a></li>
                      <li><a class="dropdown-item" href="documents/download.php?id=<?= $doc['id'] ?>"><i class="fas fa-download me-2"></i>Download</a></li>
                      <li><a class="dropdown-item" href="documents/view.php?id=<?= $doc['id'] ?>"><i class="fas fa-info-circle me-2"></i>Details</a></li>
                      <li><a class="dropdown-item" href="documents/share.php?id=<?= $doc['id'] ?>"><i class="fas fa-share me-2"></i>Share</a></li>
                      <?php if ($doc['uploaded_by'] == $_SESSION['user_id']): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="documents/archive.php?id=<?= $doc['id'] ?>"><i class="fas fa-archive me-2"></i>Archive</a></li>
                        <li><a class="dropdown-item text-danger" href="documents/delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <?php if (count($recent_user_documents) > 5): ?>
          <div class="text-center p-4">
            <button id="showMoreUserBtn" class="btn btn-show-more">
              <i class="fas fa-chevron-down me-2"></i>Show More
            </button>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const btn = document.getElementById("showMoreUserBtn");
  if (!btn) return;
  
  btn.addEventListener("click", function() {
    const rows = document.querySelectorAll(".user-doc-row");
    const expanded = btn.dataset.expanded === "true";

    if (!expanded) {
      rows.forEach(r => r.classList.remove("d-none"));
      btn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
      btn.dataset.expanded = "true";
    } else {
      rows.forEach((r, i) => { if (i >= 5) r.classList.add("d-none"); });
      btn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
      btn.dataset.expanded = "false";
    }
  });
});
</script>
