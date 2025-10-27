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

        <!-- ===== Header ===== -->
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
          <div class="d-flex align-items-center">
            <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3"
              style="background-color: rgba(42, 183, 202, 0.1); width: 48px; height: 48px;">
              <i class="fas fa-users" style="color: #2AB7CA; font-size: 1.2rem;"></i>
            </div>
            <div>
              <h4 class="mb-1 fw-bold text-dark">Documents Shared With Me</h4>
              <small class="text-muted">Files shared with you by other users</small>
            </div>
          </div>

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

        <!-- ===== Body ===== -->
        <div class="card-body p-0">
          <?php if (empty($shared_documents)): ?>
            <div class="text-center py-5 my-4">
              <i class="fas fa-share-alt fa-4x mb-3 text-muted"></i>
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
                  <th class="ps-4 py-3">Document</th>
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
                    <td class="ps-4">
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
                    <td><?php echo htmlspecialchars($doc['owner_name']); ?></td>
                    <td>
                      <?php if ($doc['category_name']): ?>
                        <span class="badge bg-primary text-white px-3 py-2"><?php echo htmlspecialchars($doc['category_name']); ?></span>
                      <?php else: ?>
                        <span class="badge bg-light text-muted px-3 py-2">Uncategorized</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge bg-<?php echo $doc['permission'] === 'download' ? 'success' : 'info'; ?>">
                        <?php echo ucfirst($doc['permission']); ?>
                      </span>
                    </td>
                    <td><span class="text-muted"><?php echo formatFileSize($doc['file_size']); ?></span></td>
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                          <i class="fas fa-ellipsis-v text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                          <li><a class="dropdown-item" href="preview.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-eye me-2 text-primary"></i>Preview</a></li>
                          <?php if ($doc['permission'] === 'download'): ?>
                            <li><a class="dropdown-item" href="download.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-download me-2 text-success"></i>Download</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item" href="view.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-info-circle me-2 text-info"></i>Details</a></li>
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
  </div>
</div>

<!-- ===== FILE REQUESTS ===== -->
<hr class="my-5">
<div class="card shadow-sm border-0 rounded-3">
  <div class="card-header bg-white border-bottom">
    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-envelope me-2 text-primary"></i>File Requests</h5>
  </div>

  <div class="card-body p-0">
    <?php
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("
      SELECT fr.*, s.full_name AS sender_name, r.full_name AS recipient_name
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
            <th>Purpose</th>
            <th>Intended Use</th>
            <th>Status</th>
            <th>Date Requested</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($requests as $req): 
            $expired = (strtotime($req['intended_date']) < strtotime('today') && $req['status'] === 'pending');
          ?>
            <tr>
              <td><?= htmlspecialchars($req['sender_name']); ?></td>
              <td><?= htmlspecialchars($req['recipient_name']); ?></td>
              <td><?= htmlspecialchars($req['description']); ?></td>
              <td><?= htmlspecialchars($req['reason']); ?></td>
              <td>
                <?php if ($expired): ?>
                  <span class="badge bg-danger">Expired (<?= date('M d, Y', strtotime($req['intended_date'])); ?>)</span>
                <?php else: ?>
                  <?= date('M d, Y', strtotime($req['intended_date'])); ?>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge bg-<?= $req['status'] === 'approved' ? 'success' : ($req['status'] === 'denied' ? 'danger' : 'warning'); ?>">
                  <?= ucfirst($req['status']); ?>
                </span>
              </td>
              <td><?= date('M d, Y h:i A', strtotime($req['created_at'])); ?></td>
              <td class="text-center">
                <?php if ($req['recipient_id'] == $user_id && $req['status'] === 'pending' && !$expired): ?>
                  <form class="d-inline" method="post" action="<?= BASE_URL; ?>api/api_manage_request.php">
                    <input type="hidden" name="id" value="<?= $req['id']; ?>">
                    <input type="hidden" name="action" value="approve">
                    <button class="btn btn-sm btn-success me-1" title="Approve"><i class="fas fa-check"></i></button>
                  </form>

                  <!-- Deny Button -->
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#denyReasonModal<?= $req['id']; ?>">
                    <i class="fas fa-times"></i>
                  </button>

                  <!-- Deny Modal -->
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
                <?php elseif ($expired): ?>
                  <span class="text-danger small">Expired</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<!-- ===== Request Modal ===== -->
<?php include '../includes/modal_request_file.php'; ?>

<style>
.file-icon-wrapper { width: 40px; height: 40px; border-radius: 8px; background-color: rgba(42,183,202,0.1); display:flex; align-items:center; justify-content:center; color:#2AB7CA; }
.document-row:hover { background-color: rgba(42,183,202,0.05) !important; }
.dropdown-item:hover { background-color: rgba(42,183,202,0.1); }
</style>

<script>
document.getElementById('requestFileForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const res = await fetch(form.action, { method: 'POST', body: new FormData(form) });
  const data = await res.json();
  alert(data.message);
  if (data.status === 'success') {
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('requestFileModal')).hide();
  }
});
</script>
