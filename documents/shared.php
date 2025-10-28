<?php
include '../includes/header.php';
include '../api/api_shared.php';

$mark_read = $db->prepare("UPDATE document_shares SET is_read = 1 WHERE shared_with = ?");
$mark_read->execute([$_SESSION['user_id']]);

?>
<div class="container-fluid py-3">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm rounded-3 border-0">

        <!-- ===== Card Header ===== -->
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
          <div class="d-flex align-items-center">
            <!-- Icon -->
            <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3"
              style="background-color: rgba(42, 183, 202, 0.1); width: 48px; height: 48px;">
              <i class="fas fa-users" style="color: #2AB7CA; font-size: 1.2rem;"></i>
            </div>

            <!-- Title + Subtitle -->
            <div>
              <h4 class="mb-1 fw-bold text-dark">Documents Shared With Me</h4>
              <small class="text-muted">Files shared with you by other users</small>
            </div>
          </div>

          <!-- Right: Button + Badge -->
          <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#requestFileModal"
              style="background: linear-gradient(135deg, #004F80, #0073b6); color: white;">
              <i class="fas fa-plus me-1"></i> Request Files
            </button>

            <?php if (!empty($shared_documents)): ?>
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #2AB7CA; color: white;">
                <?php echo count($shared_documents); ?> shared document<?php echo count($shared_documents) !== 1 ? 's' : ''; ?>
              </span>
            <?php endif; ?>
          </div>
        </div>
        <!-- ===== Card Body ===== -->
        <div class="card-body p-0">
          <?php if (empty($shared_documents)): ?>
            <div class="text-center py-5 my-4">
              <div class="empty-state-icon mb-4">
                <i class="fas fa-share-alt fa-4x" style="color: #e9ecef;"></i>
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
                        <button class="btn btn-light btn-sm rounded-circle" type="button"
                          id="sharedDocActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false"
                          style="border: none; width: 36px; height: 36px;">
                          <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: #6c757d;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3"
                          aria-labelledby="sharedDocActions<?= $doc['id'] ?>">
                          <li><a class="dropdown-item py-2" href="preview.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-eye me-2 text-primary"></i> Preview</a></li>
                          <?php if ($doc['permission'] === 'download'): ?>
                            <li><a class="dropdown-item py-2" href="download.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-download me-2 text-success"></i> Download</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item py-2" href="view.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-info-circle me-2 text-info"></i> Details</a></li>
                          <li><hr class="dropdown-divider my-1"></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
        <!-- ===== Footer ===== -->
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
             s.full_name AS sender_name, 
             r.full_name AS recipient_name,
             d.title AS document_title,
             d.original_filename,
             d.file_size
      FROM file_requests fr
      JOIN users s ON fr.sender_id = s.id
      JOIN users r ON fr.recipient_id = r.id
      LEFT JOIN documents d ON fr.document_id = d.id
      WHERE fr.sender_id = ? OR fr.recipient_id = ?
      ORDER BY fr.created_at DESC
    ");
    $stmt->execute([$user_id, $user_id]);
    $requests = $stmt->fetchAll();
    ?>
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
      <i class="fas fa-check-circle me-2"></i> File request sent successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i> Something went wrong while sending your request.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
    <?php if (empty($requests)): ?>
      <div class="text-center py-5 text-muted">No file requests yet.</div>
    <?php else: ?>
     <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Document</th>
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Date Requested</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="file-icon">
                            <i class="<?= getFileIcon(pathinfo($req['original_filename'] ?? '', PATHINFO_EXTENSION)); ?>" 
                               style="color: var(--primary-color); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($req['document_title'] ?? 'Untitled Document'); ?></div>
                            <small class="text-muted d-block">
                                <?= !empty($req['description']) ? htmlspecialchars($req['description']) . '<br>' : '' ?>
                                <?= !empty($req['reason']) ? 'Reason: ' . htmlspecialchars($req['reason']) . '<br>' : '' ?>
                                <?php if (!empty($req['deny_reason'])): ?>
                                    <span class="text-danger">Denied Reason: <?= htmlspecialchars($req['deny_reason']); ?></span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </td>
                                
                <td><?= htmlspecialchars($req['sender_name']); ?></td>
                <td><?= htmlspecialchars($req['recipient_name']); ?></td>
                <td>
                    <span class="badge rounded-pill bg-<?= $req['status'] === 'approved' ? 'success' : ($req['status'] === 'denied' ? 'danger' : 'warning'); ?>">
                        <?= ucfirst($req['status']); ?>
                    </span>
                </td>
                <td><?= date('M d, Y h:i A', strtotime($req['created_at'])); ?></td>
                <td class="text-center">
                    <?php if ($req['status'] === 'pending' && $req['recipient_id'] == $user_id): ?>
                        <!-- Approve Button -->
                        <form class="d-inline" method="post" action="<?= BASE_URL; ?>api/api_manage_request.php">
                            <input type="hidden" name="id" value="<?= $req['id']; ?>">
                            <input type="hidden" name="action" value="approve">
                            <button class="btn btn-sm btn-success me-1" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    
                        <!-- Deny Button -->
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#denyReasonModal<?= $req['id']; ?>">
                            <i class="fas fa-times"></i>
                        </button>
                    
                        <!-- Deny Modal (same as your code) -->
                        <div class="modal fade" id="denyReasonModal<?= $req['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-3">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reason for Denying</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="post" action="<?= BASE_URL; ?>api/api_manage_request.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $req['id']; ?>">
                                            <input type="hidden" name="action" value="deny">
                                            <textarea name="deny_reason" class="form-control" rows="3" placeholder="Enter your reason..." required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    
                    <?php elseif ($req['status'] === 'approved' && $req['recipient_id'] == $user_id): ?>
                        <!-- Only the requesting user sees Preview & Download -->
                        <a class="btn btn-sm btn-primary me-1" href="preview.php?id=<?= $req['document_id']; ?>" title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a class="btn btn-sm btn-success" href="download.php?id=<?= $req['document_id']; ?>" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    <?php elseif ($req['status'] === 'denied'): ?>
                        <span class="text-muted">No actions</span>
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
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold text-dark">
          <i class="fas fa-file-import me-2 text-primary"></i>Request a File
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="requestFileForm" action="<?php echo BASE_URL; ?>api/api_request_file.php" method="post">
        <div class="modal-body">

          <!-- Recipient -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="recipient_id">
              Recipient (Email or Username)
              <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
            </label>
            <?php
            $stmt = $db->prepare("SELECT id, username, email FROM users WHERE id != ?");
            $stmt->execute([$_SESSION['user_id']]);
            $users = $stmt->fetchAll();
            ?>
            <select name="recipient_id" id="recipient_id" class="form-select" required>
              <option value="">-- Select User --</option>
              <?php foreach ($users as $u): ?>
                <option value="<?= $u['id']; ?>">
                  <?= htmlspecialchars($u['username']) . " (" . htmlspecialchars($u['email']) . ")"; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Scrollable Documents -->
          <div class="mb-3">
              <label class="form-label fw-semibold" for="document_id">
                  Select Document to Request
                  <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
              </label>
              <div class="border rounded-3 p-2" style="max-height: 250px; overflow-y: auto;">
                  <?php
                  // Fetch documents NOT owned by the current user
                  $docs_stmt = $db->prepare("SELECT d.id, d.title, d.filename, d.updated_at, u.email AS owner_email 
                                             FROM documents d
                                             JOIN users u ON d.user_id = u.id
                                             WHERE d.user_id != :current_user
                                             ORDER BY d.updated_at DESC");
                  $docs_stmt->execute(['current_user' => $user_id]);
                  $documents = $docs_stmt->fetchAll();
                        
                  if (empty($documents)): ?>
                      <div class="text-muted text-center py-3">No available documents.</div>
                  <?php else: ?>
                      <?php foreach ($documents as $doc): ?>
                          <div class="form-check mb-2 d-flex align-items-center gap-2">
                              <input class="form-check-input" type="radio" name="document_id" id="doc<?= $doc['id']; ?>" value="<?= $doc['id']; ?>" required>
                              <label class="form-check-label d-flex align-items-center gap-2" for="doc<?= $doc['id']; ?>">
                                  <!-- File icon -->
                                  <i class="<?= getFileIcon(pathinfo($doc['filename'] ?? '', PATHINFO_EXTENSION)); ?>" 
                                     style="color: var(--primary-color); font-size: 1.2rem;"></i>
                                  <div>
                                      <?= htmlspecialchars($doc['title']); ?><br>
                                      <small class="text-muted">
                                          Owner: <?= htmlspecialchars($doc['owner_email']); ?><br>
                                          Updated: <?= date('M d, Y', strtotime($doc['updated_at'])); ?>
                                      </small>
                                  </div>
                              </label>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
          </div>
                      

          <!-- Purpose -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="reason">
              Purpose
              <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
            </label>
            <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Why do you need this file?" required></textarea>
          </div>

          <!-- Intended Date of Usage -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="intended_date">
              Intended Date of Usage
              <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
            </label>
            <input type="date" id="intended_date" name="intended_date" class="form-control" required>
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

<script>
document.getElementById('requestFileForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');
  
  // Show loading state
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
  submitBtn.disabled = true;

  try {
    const res = await fetch(form.action, { method: 'POST', body: formData });
    const data = await res.json();

    // Show Bootstrap alert instead of native alert
    showAlert(data.message, data.status === 'success' ? 'success' : 'danger');

    if (data.status === 'success') {
      // Reset form and close modal
      form.reset();
      const modal = bootstrap.Modal.getInstance(document.getElementById('requestFileModal'));
      modal.hide();

      // Reload page after a short delay to show the success message
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      // Reset button state if failed
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    showAlert('An error occurred while sending the request.', 'danger');
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  }
});

// Function to show Bootstrap alert
function showAlert(message, type) {
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(alertDiv);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    if (alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}
</script>

<style>
.document-row:hover { background-color: rgba(42, 183, 202, 0.05) !important; }
.file-icon-wrapper { width: 40px; height: 40px; border-radius: 8px; background-color: rgba(42, 183, 202, 0.1); display: flex; align-items: center; justify-content: center; color: #2AB7CA; }
.avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.empty-state-icon { opacity: 0.5; }
.dropdown-item:hover { background-color: rgba(42, 183, 202, 0.1); }
</style>