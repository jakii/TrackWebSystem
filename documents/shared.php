  <?php
  include '../includes/header.php';
  include '../api/api_shared.php';
  ?>
  <div class=" mt-2 fade-in delay-4">
    <div class="card shadow rounded-4 border-0">
      <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
        <div>
          <h5 class="mb-0">
            <i class="fas fa-users me-2" style="color: #2AB7CA;"></i> Documents Shared With Me
          </h5>
          <small class="text-muted">Files shared to you by other users</small>
        </div>
        <?php if (!empty($shared_documents)): ?>
          <span class="badge rounded-pill" style="background-color: #2AB7CA; color: white; font-weight: 500;">
            <?php echo count($shared_documents); ?> shared
          </span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <?php if (empty($shared_documents)): ?>
          <div class="text-center py-4">
            <i class="fas fa-share-alt fa-3x text-muted mb-3"></i>
            <h6 class="text-muted">No shared documents</h6>
            <p class="text-muted">Files that others send you will show up here.</p>
          </div>
        <?php else: ?>
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Document</th>
                  <th>Owner</th>
                  <th>Category</th>
                  <th>Permission</th>
                  <th>Size</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($shared_documents as $doc): ?>
                  <tr>
                    <td>
                      <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                      <?php echo htmlspecialchars($doc['title']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($doc['owner_name']); ?></td>
                    <td>
                      <?php if ($doc['category_name']): ?>
                        <span class="badge" style="background-color: <?php echo $doc['category_color']; ?>;">
                          <?php echo htmlspecialchars($doc['category_name']); ?>
                        </span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Uncategorized</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge bg-<?php echo $doc['permission'] === 'download' ? 'success' : 'info'; ?>">
                        <?php echo $doc['permission'] === 'download' ? 'View & Download' : 'View Only'; ?>
                      </span>
                    </td>
                    <td><?php echo formatFileSize($doc['file_size']); ?></td>
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="sharedDocActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                          <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sharedDocActions<?= $doc['id'] ?>">
                          <li><a class="dropdown-item" href="preview.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-eye me-2"></i> Preview</a></li>
                          <?php if ($doc['permission'] === 'download'): ?>
                            <li><a class="dropdown-item" href="documents/download.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-download me-2"></i> Download</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item" href="documents/view.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-info-circle me-2"></i> Details</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
        <?php endif; ?>
      </div>
    </div>
  </div>