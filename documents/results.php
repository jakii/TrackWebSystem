<?php
require_once '../config/config.php';
include '../api/api_result.php';
requireAuth();
require_once '../includes/header.php';
$search = trim($_GET['search'] ?? '');
?>
<div class="row">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-search me-2" style="color: #2AB7CA;"></i>Search Results
        </h1>
        <hr>
    </div>
</div>

<div class="card shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color: #2AB7CA;"></i> Results for "<?php echo htmlspecialchars($search); ?>"
            </h5>
            <small class="text-muted">
                Showing <?php echo number_format($total_results); ?> 
                <?php echo ($total_results == 1 ? 'result' : 'results'); ?>
            </small>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($results)): ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No results found</h5>
                <?php if ($search): ?>
                    <p class="text-muted">Try adjusting your search criteria or use advanced search.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Uploader</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $item): ?>
                    <tr ondblclick="handleRowClick('<?= $item['type'] ?>', <?= $item['id'] ?>)" style="cursor:pointer;">
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if ($item['type'] === 'folder'): ?>
                                    <i class="fas fa-folder text-warning fa-2x me-2"></i>
                                <?php else: ?>
                                    <i class="<?php echo getFileIcon(pathinfo($item['filename'] ?? '', PATHINFO_EXTENSION)); ?> me-2 fa-2x"></i>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </div>
                                    <?php if ($item['type'] === 'document' && !empty($item['tags'])): ?>
                                        <small class="text-muted">Tags: <?php echo htmlspecialchars($item['tags']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <td>
                            <?php if ($item['type'] === 'folder'): ?>
                                <span class="badge bg-warning text-dark">Folder</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark">Document</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($item['category_name']): ?>
                                <span class="badge" style="background-color: <?php echo $item['category_color']; ?>;">
                                    <?php echo htmlspecialchars($item['category_name']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php 
                            echo ($item['type'] === 'folder') 
                                ? '—' 
                                : formatFileSize($item['file_size'] ?? 0);
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($item['uploader_name']); ?></td>

                        <td>
                            <span data-bs-toggle="tooltip" title="<?php echo date('F j, Y g:i A', strtotime($item['created_at'])); ?>">
                                <?php echo date('M j, Y', strtotime($item['created_at'])); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" id="actions<?= $item['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                    <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actions<?= $item['id'] ?>">

                                    <?php if ($item['type'] === 'folder'): ?>
                                        <!-- FOLDER ACTIONS -->
                                        <li>
                                            <a class="dropdown-item text-primary" href="browse.php?folder=<?= $item['id'] ?>">
                                                <i class="fas fa-folder-open me-2"></i> Open Folder
                                            </a>
                                        </li>
                                        
                                    <?php else: ?>
                                        <!-- DOCUMENT ACTIONS -->
                                        <?php if (!isAdmin() && $item['uploaded_by'] != $_SESSION['user_id']): ?>
                                            <li>
                                                <a class="dropdown-item text-primary" href="#" onclick="requestFile(<?= $item['id'] ?>)">
                                                    <i class="fas fa-key me-2"></i> Request File
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <a class="dropdown-item" href="view.php?id=<?= $item['id'] ?>">
                                                <i class="fas fa-info-circle me-2"></i> View Details
                                            </a>
                                        </li>
                                        <?php if ($item['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                            <li>
                                                <a class="dropdown-item" href="share.php?id=<?= $item['id'] ?>">
                                                    <i class="fas fa-share me-2"></i> Share
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="preview.php?id=<?= $item['id'] ?>"><i class="fas fa-eye me-2"></i>Preview</a></li>
                                            <li><a class="dropdown-item" href="download.php?id=<?= $item['id'] ?>"><i class="fas fa-download me-2"></i>Download</a></li>
                                            <li><a class="dropdown-item" href="archive.php?id=<?= $item['id'] ?>"><i class="fas fa-archive me-2"></i>Archive</a></li>
                                            <li><a class="dropdown-item text-danger" href="delete.php?id=<?= $item['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>                
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div class="modal fade" id="requestFileModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content shadow border-0 rounded-4">
              <div class="modal-header" style="background-color:#004F80;color:white;">
                <h5 class="modal-title">
                  <i class="fas fa-key me-2"></i> Request File Access
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <form id="requestFileForm">
                <div class="modal-body">
                  <input type="hidden" name="document_id" id="request_document_id">

                  <div class="mb-3">
                    <label class="form-label fw-bold">Document Title</label>
                    <input type="text" class="form-control" id="request_document_title" name="document_title" readonly>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold">Uploader Email</label>
                    <input type="email" class="form-control" id="request_uploader_email" name="uploader_email" readonly>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold">Reason for Request</label>
                    <textarea class="form-control" id="request_reason" name="reason" rows="3" required></textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold">Intended Date of Use</label>
                    <input type="date" class="form-control" id="request_intended_date" name="intended_date" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn" style="background-color:#004F80;color:white;">
                    <i class="fas fa-paper-plane me-2"></i>Send Request
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</div>
<script>
    async function requestFile(documentId) {
      try {
        // Fetch document info
        const res = await fetch(`../api/get_document_info.php?id=${documentId}`);
        const data = await res.json();
    
        if (!data || data.error) {
          alert("Error fetching document details.");
          return;
        }
    
        // Fill modal fields
        document.getElementById("request_document_id").value = data.id;
        document.getElementById("request_document_title").value = data.title;
        document.getElementById("request_uploader_email").value = data.uploader_email;
    
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById("requestFileModal"));
        modal.show();
      } catch (err) {
        console.error(err);
        alert("Unable to load file info.");
      }
    }
    
    // Handle submit
    document.getElementById("requestFileForm").addEventListener("submit", async (e) => {
      e.preventDefault();
    
      const form = e.target;
      const formData = new FormData(form);
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
    
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
      submitBtn.disabled = true;
    
      try {
        const res = await fetch("../api/api_request_file.php", {
          method: "POST",
          body: formData,
        });
    
        const data = await res.json();
    
        if (data.status === "success") {
          showAlert("File request sent successfully!", "success");
          form.reset();
          setTimeout(() => {
            window.location.href = "../documents/shared.php";
          }, 1500);
        } else {
          showAlert("Error: " + data.message, "danger");
        }
      } catch (err) {
        console.error(err);
        showAlert("Network error. Please try again.", "danger");
      } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    });
    function showAlert(message, type = "info") {
      const alertDiv = document.createElement("div");
      alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      alertDiv.style.cssText =
        "top: 20px; right: 20px; z-index: 1055; min-width: 300px;";
      alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.body.appendChild(alertDiv);
      setTimeout(() => alertDiv.remove(), 4000);
    }
</script>
