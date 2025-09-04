<?php
include 'api/api_dashboard.php';
include 'includes/header.php';
?>
<div class="row mb-3 fade-in delay-1">
  <div class="col-12">
    <h2 class="mb-0" style="color: #2F4858;">
      <i class="fas fa-tachometer-alt me-2" style="color: #2AB7CA;"></i> Dashboard
    </h2>
    <hr>
  </div>
</div>
<div class="row g-3 mb-4 fade-in delay-2">
  <?php if (isAdmin()): ?>
    <div class="col-md-4">
      <div class="card text-white shadow rounded-4 border-0 " style="background-color: #004F80;">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-file-alt fa-2x me-3"style="color: #2AB7CA;"></i>
          <div>
            <h6 class="mb-0">Total Documents</h6>
            <h4>
              <span class="count-up" data-target="<?php echo $total_documents; ?>">0</span>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white shadow rounded-4 border-0" style="background-color: #004F80;">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-database fa-2x me-3"style="color: #2AB7CA;"></i>
          <div>
            <h6 class="mb-0">Storage Used</h6>
            <h4>
                <span class="count-bytes" data-bytes="<?php echo $total_size; ?>">0 KB</span>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white shadow rounded-4 border-0" style="background-color: #004F80;">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-folder-tree fa-2x me-3"style="color: #2AB7CA;"></i>
          <div>
            <h6 class="mb-0">Categories</h6>
            <h4>
              <span class="count-up" data-target="<?php echo count($categories); ?>">0</span>
            </h4>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<div class="row g-2 fade-in delay-3">
  <link href="assets/css/custom.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="assets/js/weekly_uploads.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/statistics.js"></script>
   <?php if (isAdmin()): ?>
    <?php if (!empty($weekly_uploads) && array_sum($weekly_uploads) > 0): ?>
      <div class="col-md-8">
        <div class="card h-100 shadow rounded-4 border-0">
          <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
            <h5 class="mb-0">
              <i class="fas fa-chart-line me-2" style="color: #004F80;"></i> Weekly Uploads
            </h5>
            <a href="all_reports.php" class="btn btn-sm rounded-pill px-3" style="background-color: #2AB7CA; color: white; font-weight: 500;">
              View All
            </a>
          </div>
          <div class="card-body">
            <canvas id="weeklyUploadsChart"
              height="250"
              data-labels='<?php echo json_encode(array_keys($weekly_uploads)); ?>'
              data-data='<?php echo json_encode(array_values($weekly_uploads)); ?>'>
            </canvas>
          </div>
        </div> 
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow rounded-4 border-0">
          <div class="card-header bg-white border-bottom rounded-top-4">
            <h5 class="mb-0">
              <i class="fas fa-chart-area me-2" style="color: #2AB7CA;"></i> Statistics
            </h5>
          </div>
          <div class="card-body">
            <canvas id="statisticsChart" height="250"></canvas>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div>
        <?php include 'recent_documents_partial.php';?>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div>
      <?php include 'recent_documents_partial.php';?>
    </div>
  <?php endif; ?>
 <?php if (isAdmin()): ?>
  <div class="card shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
      <div>
        <h5 class="mb-0">
          <i class="fas fa-folder-open me-2" style="color: #2AB7CA;"></i> All Documents
        </h5>
        <small class="text-muted">All your uploaded files in one place</small>
      </div>
    </div>
    <div class="card-body">
      <?php if (empty($recent_documents)): ?>
        <div class="text-center py-4">
          <i class="fas fa-file-plus fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">No documents uploaded yet</h5>
          <p class="text-muted">Upload your documents.</p>
          <a href="documents/browse.php" class="btn" style="background-color: #004F80; color: white;">
            <i class="fas fa-folder-open me-2" style="color: white;"></i>Browse Documents
          </a>
        </div>
      <?php else: ?>
        <!-- Wrapper -->
        <div id="documentsWrapper" style="overflow: hidden;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3">Document</th>
                <th>Category</th>
                <th>Size</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_documents as $i => $doc): ?>
                <tr class="doc-row <?= $i >= 5 ? 'd-none' : '' ?>">
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

        <!-- Show More Button -->
        <?php if (count($recent_documents) > 5): ?>
          <div class="text-center mt-2">
            <button id="showMoreBtn" class="btn btn-sm rounded-pill px-3" style="background-color: #2AB7CA; color: white; font-weight: 500;">
              Show More
            </button>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const btn = document.getElementById("showMoreBtn");
  const wrapper = document.getElementById("documentsWrapper");

  if (btn) {
    btn.addEventListener("click", function() {
      // show hidden rows
      document.querySelectorAll(".doc-row.d-none").forEach(row => row.classList.remove("d-none"));

      // make scrollable
      wrapper.style.maxHeight = "300px";
      wrapper.style.overflowY = "auto";

      // hide button
      btn.style.display = "none";
    });
  }
});
</script>
