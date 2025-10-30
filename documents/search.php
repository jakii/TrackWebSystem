<?php
require_once '../includes/header.php';
include '../api/api_search.php';
?>
<div class="row">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-search me-2" style="color: #004F80;"></i>Search Documents
        </h1>
        <hr>
    </div>
</div>

<!-- Search Form -->
<div class="card mb-4 shadow rounded-4 border-0">
  <div class="card-body">
    <form method="GET" class="row g-3" id="searchForm">

      <!-- Search -->
      <div class="col-md-4">
        <label for="search" class="form-label">Search</label>
        <input type="text" class="form-control rounded-pill" id="search" name="search"
               value="<?= htmlspecialchars($search ?? '') ?>"
               placeholder="Search by title, description, tags, or filename">
      </div>

      <!-- Category -->
      <div class="col-md-2">
        <label for="category" class="form-label">Category</label>
        <select class="form-select rounded-pill" id="category" name="category">
          <option value="">All Categories</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>" 
                    <?= ($category_id == $category['id']) ? 'selected' : ''; ?>>
              <?= htmlspecialchars($category['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- File Type -->
      <div class="col-md-2">
        <label for="file_type" class="form-label">File Type</label>
        <select class="form-select rounded-pill" id="file_type" name="file_type">
          <option value="">All Types</option>
          <option value="pdf" <?= ($file_type === 'pdf') ? 'selected' : ''; ?>>PDF</option>
          <option value="doc" <?= ($file_type === 'doc') ? 'selected' : ''; ?>>Word</option>
          <option value="image" <?= ($file_type === 'image') ? 'selected' : ''; ?>>Images</option>
          <option value="excel" <?= ($file_type === 'excel') ? 'selected' : ''; ?>>Excel</option>
          <option value="powerpoint" <?= ($file_type === 'powerpoint') ? 'selected' : ''; ?>>PowerPoint</option>
        </select>
      </div>

      <!-- Date Filter -->
      <div class="col-md-2">
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control rounded-pill" id="date" name="date"
               value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
      </div>

      <!-- Order -->
      <div class="col-md-2">
        <label for="order" class="form-label">Order</label>
        <select class="form-select rounded-pill" id="order" name="order">
          <option value="desc" <?= ($sort_order === 'desc') ? 'selected' : ''; ?>>Descending</option>
          <option value="asc" <?= ($sort_order === 'asc') ? 'selected' : ''; ?>>Ascending</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="col-12">
        <button type="submit" class="btn me-2 rounded-pill" style="background-color: #004F80; color: white;">
          <i class="fas fa-search me-2"></i>Search
        </button>
        <a href="search.php" class="btn btn-outline-secondary rounded-pill">
          <i class="fas fa-times me-2"></i>Clear
        </a>
      </div>

    </form>
  </div>
</div>

<!-- Auto-submit filters JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('searchForm');

    // Dropdown filters: submit on change
    ['category', 'file_type', 'order'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', () => form.submit());
        }
    });

    // Date picker: submit immediately when a date is selected
    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.addEventListener('input', () => {
            if (dateInput.value) form.submit();
        });
    }
});
</script>



<!-- Results -->
<div class="card shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color: #2AB7CA;"></i> Search Results
            </h5>
            <small class="text-muted">Showing <?php echo number_format($total_results); ?> document(s)</small>
        </div>
        <?php if ($total_results > 0): ?>
        <span class="badge rounded-pill" style="background-color: #2AB7CA; color: white; font-weight: 500;">
            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
        </span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($documents)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No documents found</h5>
            <p class="text-muted">
                <?php if ($search || $category_id || $file_type): ?>
                Try adjusting your search criteria.
                <?php endif; ?>
            </p>
        </div>
        <?php else: ?>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Document</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Uploader</th>
                        <th>Date</th>
                        <th>Downloads</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($doc['title']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($doc['original_filename']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($doc['category_name']): ?>
                            <span class="badge" style="background-color: <?php echo $doc['category_color']; ?>">
                                <?php echo htmlspecialchars($doc['category_name']); ?>
                            </span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Uncategorized</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatFileSize($doc['file_size']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($doc['uploader_name']); ?>
                            <?php if ($doc['is_public']): ?>
                            <span class="badge bg-success ms-1">Public</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span data-bs-toggle="tooltip" title="<?php echo date('F j, Y g:i A', strtotime($doc['created_at'])); ?>">
                                <?php echo date('M j, Y', strtotime($doc['created_at'])); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($doc['download_count']); ?></td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" id="docActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;" title="Actions">
                                    <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="docActions<?= $doc['id'] ?>">
                                    <?php if (!isAdmin() && $doc['uploaded_by'] != $_SESSION['user_id']): ?>
                                        <li>
                                            <a class="dropdown-item text-primary" href="#" onclick="requestFile(<?= $doc['id'] ?>)" data-bs-toggle="tooltip" title="Request File">
                                                <i class="fas fa-key me-2"></i> Request File
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <li>
                                        <a class="dropdown-item" href="view.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-info-circle me-2"></i> View Details
                                        </a>
                                    </li>
                                    
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id']): ?>
                                        <li>
                                            <a class="dropdown-item" href="share.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="Share">
                                                <i class="fas fa-share me-2"></i> Share
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                        <li><a class="dropdown-item" href="preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i>Preview</a></li>
                                        <li><a class="dropdown-item" href="download.php?id=<?= $doc['id'] ?>"><i class="fas fa-download me-2"></i>Download</a></li>
                                        <li><a class="dropdown-item" href="archive.php?id=<?= $doc['id'] ?>"><i class="fas fa-archive me-2"></i>Archive</a></li>
                                        <li>
                                            <a class="dropdown-item text-danger" 
                                               href="delete.php?id=<?= $doc['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                                               data-bs-toggle="tooltip" title="Delete">
                                               <i class="fas fa-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>   
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Search results pagination">
            <ul class="pagination justify-content-center mb-0">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Previous</a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
    </div>
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