<div class="card shadow rounded-4 border-0">
  <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
    <div>
      <h5 class="mb-0">
        <i class="fas fa-folder-open me-2" style="color: #2AB7CA;"></i> All Documents
      </h5>
      <small class="text-muted">All your uploaded files in one place</small>
    </div>
    <a href="documents/search.php" class="btn btn-sm rounded-pill px-3" style="background-color: #2AB7CA; color: white; font-weight: 500;">View All</a>
  </div>

  <div class="card-body p-0">
    <?php if (empty($recent_documents)): ?>
      <div class="text-center py-4">
        <i class="fas fa-file-plus fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No documents uploaded yet</h5>
        <p class="text-muted">Upload your documents.</p>
        <a href="documents/browse.php" class="btn" style="background-color: #004F80; color: white;">
          <i class="fas fa-folder-open me-2"></i>Browse Documents
        </a>
      </div>
    <?php else: ?>
      <!-- ✅ Scrollable Table Wrapper -->
      <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light sticky-top">
            <tr>
              <th class="ps-3">Document</th>
              <th>Category</th>
              <th>Size</th>
              <th>Date</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_documents as $doc): ?>
              <tr>
                <td>
                  <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                  <?php echo htmlspecialchars($doc['title']); ?>
                </td>
                <td>
                  <?php if ($doc['category_name']): ?>
                    <span class="badge" style="background-color: <?php echo $doc['category_color']; ?>;">
                      <?php echo htmlspecialchars($doc['category_name']); ?>
                    </span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Uncategorized</span>
                  <?php endif; ?>
                </td>
                <td><?php echo formatFileSize($doc['file_size']); ?></td>
                <td><?php echo date('M j, Y g:i A', strtotime($doc['created_at'])); ?></td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle" type="button" id="docActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                      <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="docActions<?= $doc['id'] ?>">
                      <li><a class="dropdown-item" href="documents/preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i> Preview</a></li>
                      <li><a class="dropdown-item" href="documents/download.php?id=<?= $doc['id'] ?>"><i class="fas fa-download me-2"></i> Download</a></li>
                      <li><a class="dropdown-item" href="documents/view.php?id=<?= $doc['id'] ?>"><i class="fas fa-info-circle me-2"></i> Details</a></li>
                      <li><a class="dropdown-item" href="documents/share.php?id=<?= $doc['id'] ?>"><i class="fas fa-share me-2"></i> Share</a></li>
                      <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="documents/delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i> Delete</a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      
    <?php endif; ?>
  </div>
</div>

