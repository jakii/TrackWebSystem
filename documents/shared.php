<?php
include '../includes/header.php';
include '../api/api_shared.php';
?>
<div class="container-fluid py-3">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm rounded-3 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
          <div class="d-flex align-items-center">
            <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3" style="background-color: rgba(42, 183, 202, 0.1); width: 48px; height: 48px;">
              <i class="fas fa-users" style="color: #2AB7CA; font-size: 1.2rem;"></i>
            </div>
            <div>
              <h4 class="mb-0 fw-bold text-dark">Documents Shared With Me</h4>
              <small class="text-muted">Files shared with you by other users</small>
            </div>
          </div>
          <?php if (!empty($shared_documents)): ?>
            <div class="d-flex align-items-center">
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #2AB7CA; color: white;">
                <?php echo count($shared_documents); ?> shared document<?php echo count($shared_documents) !== 1 ? 's' : ''; ?>
              </span>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="card-body p-0">
          <?php if (empty($shared_documents)): ?>
            <div class="text-center py-5 my-4">
              <div class="empty-state-icon mb-4">
                <i class="fas fa-share-alt fa-4x text-light" style="color: #e9ecef;"></i>
              </div>
              <h5 class="text-muted mb-2">No shared documents</h5>
              <p class="text-muted mb-4">Files that others share with you will appear here.</p>
              <button class="btn btn-outline-primary" onclick="location.reload()">
                <i class="fas fa-redo me-2"></i>Refresh
              </button>
            </div>
          <?php else: ?>
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4 py-3 fw-semibold text-dark">Document</th>
                  <th class="py-3 fw-semibold text-dark">Owner</th>
                  <th class="py-3 fw-semibold text-dark">Category</th>
                  <th class="py-3 fw-semibold text-dark">Permission</th>
                  <th class="py-3 fw-semibold text-dark">Size</th>
                  <th class="text-center py-3 fw-semibold text-dark">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($shared_documents as $doc): ?>
                  <tr class="document-row">
                    <td class="ps-4 py-3">
                      <div class="d-flex align-items-center">
                        <div class="file-icon-wrapper me-3">
                          <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?>"></i>
                        </div>
                        <div>
                          <div class="fw-medium text-dark"><?php echo htmlspecialchars($doc['title']); ?></div>
                          <small class="text-muted"><?php echo date('M j, Y', strtotime($doc['created_at'])); ?></small>
                        </div>
                      </div>
                    </td>
                    <td class="py-3">
                      <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-2">
                          <span class="text-dark fw-medium"><?php echo strtoupper(substr($doc['owner_name'], 0, 1)); ?></span>
                        </div>
                        <?php echo htmlspecialchars($doc['owner_name']); ?>
                      </div>
                    </td>
                    <td class="py-3">
                      <?php if ($doc['category_name']): ?>
                        <span class="badge rounded-pill px-3 py-2" style="background-color: <?php echo $doc['category_color']; ?>; color: white;">
                          <?php echo htmlspecialchars($doc['category_name']); ?>
                        </span>
                      <?php else: ?>
                        <span class="badge rounded-pill bg-light text-muted px-3 py-2">Uncategorized</span>
                      <?php endif; ?>
                    </td>
                    <td class="py-3">
                      <span class="badge rounded-pill px-3 py-2 bg-<?php echo $doc['permission'] === 'download' ? 'success' : 'info'; ?>">
                        <i class="fas fa-<?php echo $doc['permission'] === 'download' ? 'download' : 'eye'; ?> me-1"></i>
                        <?php echo $doc['permission'] === 'download' ? 'View & Download' : 'View Only'; ?>
                      </span>
                    </td>
                    <td class="py-3">
                      <span class="text-muted"><?php echo formatFileSize($doc['file_size']); ?></span>
                    </td>
                    <td class="text-center py-3">
                      <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="sharedDocActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; width: 36px; height: 36px;">
                          <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: #6c757d;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" aria-labelledby="sharedDocActions<?= $doc['id'] ?>">
                          <li><a class="dropdown-item py-2" href="preview.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-eye me-2 text-primary"></i> Preview</a></li>
                          <?php if ($doc['permission'] === 'download'): ?>
                            <li><a class="dropdown-item py-2" href="download.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-download me-2 text-success"></i> Download</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item py-2" href="view.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-info-circle me-2 text-info"></i> Details</a></li>
                          <li><hr class="dropdown-divider my-1"></li>
                          <li>
                            <a class="dropdown-item py-2 text-danger" href="#"
                               onclick="removeAccess(<?= $doc['id']; ?>)">
                               <i class="fas fa-ban me-2"></i> Remove Access
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
        
        <?php if (!empty($shared_documents)): ?>
          <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted small">
                Showing <?php echo count($shared_documents); ?> shared document<?php echo count($shared_documents) !== 1 ? 's' : ''; ?>
              </div>
              <div>
                <button class="btn btn-sm btn-outline-secondary me-2">
                  <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
                <!-- ✅ Trigger Modal -->
                <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#requestFileModal"
                  style="background: linear-gradient(135deg, #004F80, #0073b6); color: white;">
                  <i class="fas fa-plus me-1"></i> Request Files
                </button>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<hr class="my-5">
<div class="card shadow-sm border-0 rounded-3">
  <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold text-dark">
      <i class="fas fa-envelope me-2 text-primary"></i>File Requests
    </h5>
    <small class="text-muted">View and manage incoming or outgoing file requests</small>
  </div>

  <div class="card-body p-0">
    <?php
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("
      SELECT fr.*, 
             s.username AS sender_name, 
             r.username AS recipient_name
      FROM file_requests fr
      JOIN users s ON fr.sender_id = s.id
      JOIN users r ON fr.recipient_id = r.id
      WHERE fr.sender_id = ? OR fr.recipient_id = ?
      ORDER BY fr.created_at DESC
    ");
    $stmt->execute([$user_id, $user_id]);
    $requests = $stmt->fetchAll();
    ?>

    <?php if (empty($requests)): ?>
      <div class="text-center py-5 text-muted">No file requests yet.</div>
    <?php else: ?>
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>From</th>
            <th>To</th>
            <th>Description</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($requests as $req): ?>
            <tr>
              <td><?= htmlspecialchars($req['sender_name']); ?></td>
              <td><?= htmlspecialchars($req['recipient_name']); ?></td>
              <td><?= htmlspecialchars($req['description']); ?></td>
              <td><?= htmlspecialchars($req['reason']); ?></td>
              <td>
                <span class="badge rounded-pill bg-<?= $req['status'] === 'approved' ? 'success' : ($req['status'] === 'denied' ? 'danger' : 'warning'); ?>">
                  <?= ucfirst($req['status']); ?>
                </span>
              </td>
              <td><?= date('M d, Y h:i A', strtotime($req['created_at'])); ?></td>
                <td class="text-center">
                  <?php if ($req['recipient_id'] == $user_id && $req['status'] === 'pending'): ?>
                    <!-- Approve / Deny Buttons -->
                    <form class="d-inline" method="post" action="<?= BASE_URL; ?>api/api_manage_request.php">
                      <input type="hidden" name="id" value="<?= $req['id']; ?>">
                      <button name="action" value="approve" class="btn btn-sm btn-success me-1" title="Approve">
                        <i class="fas fa-check"></i>
                      </button>
                      <button name="action" value="deny" class="btn btn-sm btn-danger" title="Deny">
                        <i class="fas fa-times"></i>
                      </button>
                    </form>
                  
                  <?php elseif ($req['status'] === 'denied' && ($req['sender_id'] == $user_id || isAdmin())): ?>
                    <!-- ✅ Delete Button when Denied -->
                    <form class="d-inline" method="post" action="<?= BASE_URL; ?>api/api_delete_request.php" onsubmit="return confirm('Delete this denied request?');">
                      <input type="hidden" name="id" value="<?= $req['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  
                  <?php else: ?>
                    <small class="text-muted">No actions</small>
                  <?php endif; ?>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<!-- Request File Modal -->
<div class="modal fade" id="requestFileModal" tabindex="-1" aria-labelledby="requestFileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold text-dark">
          <i class="fas fa-file-import me-2 text-primary"></i>Request a File
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="requestFileForm" action="<?php echo BASE_URL; ?>api/api_request_file.php" method="post">
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label fw-semibold">Recipient (Email or Username)</label>
            <input type="text" name="recipient_identifier" class="form-control" placeholder="Enter email or username" required>
            <small class="text-muted">We'll find the user automatically.</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">File Description</label>
            <input type="text" name="description" class="form-control" placeholder="e.g., Project Proposal PDF" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Reason</label>
            <textarea name="reason" class="form-control" rows="3" placeholder="Why do you need this file?" required></textarea>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"
            style="background: linear-gradient(135deg, #004F80, #0073b6); border: none;">
            <i class="fas fa-paper-plane me-2"></i>Send Request
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<style>
.document-row:hover { background-color: rgba(42, 183, 202, 0.05) !important; }
.file-icon-wrapper { width: 40px; height: 40px; border-radius: 8px; background-color: rgba(42, 183, 202, 0.1); display: flex; align-items: center; justify-content: center; color: #2AB7CA; }
.avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.empty-state-icon { opacity: 0.5; }
.dropdown-item:hover { background-color: rgba(42, 183, 202, 0.1); }
</style>
<script>
document.getElementById('requestFileForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  const res = await fetch(form.action, { method: 'POST', body: formData });
  const data = await res.json();

  alert(data.message);

  if (data.status === 'success') {
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('requestFileModal')).hide();
  }
});

async function removeAccess(documentId) {
  if (!confirm("Are you sure you want to remove access to this document?")) return;

  const formData = new FormData();
  formData.append('id', documentId);

  const res = await fetch('<?= BASE_URL; ?>api/api_remove_shared_access.php', {
    method: 'POST',
    body: formData
  });

  const data = await res.json();
  alert(data.message);

  if (data.status === 'success') {
    location.reload();
  }
}

</script>

