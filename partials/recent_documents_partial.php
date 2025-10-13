<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dashboard.css">

<div class="documents-table">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h5 class="mb-1">
          <i class="fas fa-folder-open me-2" style="color: #2AB7CA;"></i>
          All Documents
        </h5>
        <small class="text-muted">All your uploaded files in one place</small>
      </div>
      <a href="documents/search.php" class="btn btn-accent-custom btn-sm">
        <i class="fas fa-folder me-2"></i>View All
      </a>
    </div>

    <div class="card-body p-0">
      <?php if (empty($recent_documents)): ?>
        <div class="empty-state">
          <i class="fas fa-file-circle-plus"></i>
          <p>No documents uploaded yet.</p>
          <a href="documents/upload.php" class="btn btn-accent-custom">
            <i class="fas fa-upload me-2"></i>Upload Document
          </a>
        </div>
      <?php else: ?>
        <!-- ✅ FIX: Added ID for JS -->
        <div id="documentsWrapper">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Document</th>
                <th>Category</th>
                <th>Size</th>
                <th>Owner</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_documents as $i => $doc): ?>
                <tr class="doc-row <?= $i >= 5 ? 'd-none' : '' ?>">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="file-icon">
                        <i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?>" style="color: var(--primary-color);"></i>
                      </div>
                      <div>
                        <div class="fw-semibold text-dark"><?= htmlspecialchars($doc['title']); ?></div>
                        <small class="text-muted">
                          <i class="fas fa-user me-1"></i><?= htmlspecialchars($doc['uploader_name']); ?>
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
                  <td class="text-muted"><?= htmlspecialchars($doc['uploader_name']); ?></td>
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
                        <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="documents/delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                        <?php endif; ?>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if (count($recent_documents) > 5): ?>
          <div class="text-center p-4">
            <button id="showMoreBtn" class="btn btn-show-more">
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
  const btn = document.getElementById("showMoreBtn");
  const wrapper = document.getElementById("documentsWrapper");

  if (btn && wrapper) {
    btn.addEventListener("click", function() {
      const rows = document.querySelectorAll(".doc-row");
      const expanded = btn.dataset.expanded === "true";

      if (!expanded) {
        // Expand
        rows.forEach(row => row.classList.remove("d-none"));
        wrapper.style.maxHeight = "400px";
        wrapper.style.overflowY = "auto";
        btn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
        btn.dataset.expanded = "true";
      } else {
        // Collapse
        rows.forEach((row, index) => {
          if (index >= 5) row.classList.add("d-none");
        });
        wrapper.style.maxHeight = "";
        wrapper.style.overflowY = "";
        btn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
        btn.dataset.expanded = "false";
      }
    });
  }
});
</script>
