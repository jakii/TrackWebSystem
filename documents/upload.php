<?php
require_once '../includes/header.php';
require_once '../config/database.php';
include '../api/api_upload.php';

$folder_query = $db->prepare("SELECT id, name, parent_id FROM folders ORDER BY name ASC");
$folder_query->execute();
$folders = $folder_query->fetchAll(PDO::FETCH_ASSOC);

$foldersById = [];
foreach ($folders as $f) {
    $foldersById[$f['id']] = $f;
}

function getFolderPath($folderId, $foldersById) {
    $path = [];
    while ($folderId && isset($foldersById[$folderId])) {
        $folder = $foldersById[$folderId];
        array_unshift($path, $folder['name']);
        $folderId = $folder['parent_id'] ?? null;
    }
    return implode('/', $path);
}

if (isset($_POST['upload'])) {
    if (empty($error)) {
        echo "<script>window.location.href='../dashboard.php?status=success';</script>";
        exit;
    } else {
        echo "<script>window.location.href='upload.php?status=error';</script>";
        exit;
    }
}
?>
<div class="row mb-5 mt-4 justify-content-center">
  <script src="../assets/js/upload.js"></script>
  <div class="col-lg-8">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="card-header text-white py-3" style="background: linear-gradient(90deg, #004F80, #0078AA);">
        <h4 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Document</h4>
      </div>
      <div class="card-body p-4 bg-light">
        <form method="POST" action="../api/api_upload.php" enctype="multipart/form-data" class="needs-validation" novalidate>
          <!-- Drag and Drop Upload -->
          <div class="mb-4">
            <label class="form-label fw-semibold" for="documents">Upload Files <span class="text-danger">*</span></label>
            <div id="drop-zone"
              class="rounded-4 p-5 text-center bg-white border border-2 border-dashed shadow-sm hover-shadow"
              style="cursor:pointer; transition: all 0.3s;">
              <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
              <p class="fw-semibold mb-1">Drag & drop your files here</p>
              <small class="text-muted">or click to browse</small>
            </div>
            <input type="file" class="form-control d-none" id="documents" name="documents[]" multiple required>
            <div id="file-list" class="mt-2 small text-muted"></div>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Description</label>
            <textarea class="form-control rounded-3" id="description" name="description" rows="3"
              placeholder="Enter a short description..."></textarea>
          </div>

          <!-- Folder -->
          <div class="mb-3">
            <label for="folder_id" class="form-label fw-semibold">Destination Folder <span class="text-danger">*</span></label>
            <select class="form-select rounded-3" id="folder_id" name="folder_id" required>
              <option value="">Select a folder</option>
              <?php foreach ($folders as $folder): ?>
                <option value="<?= $folder['id'] ?>"><?= htmlspecialchars(getFolderPath($folder['id'], $foldersById)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
            <select class="form-select rounded-3" id="category_id" name="category_id" required>
              <option value="">Select a category</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Tags -->
          <div class="mb-3">
            <label for="tags" class="form-label fw-semibold">Tags</label>
            <input type="text" class="form-control rounded-3" id="tags" name="tags"
              placeholder="Enter tags separated by commas">
          </div>

          <!-- Public Option -->
          <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="is_public" name="is_public">
            <label class="form-check-label" for="is_public">Make this document public</label>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-end align-items-center gap-2">
            <a href="../dashboard.php" class="btn btn-outline-secondary rounded-3 px-4">Cancel</a>
            <button type="button" id="uploadBtn" class="btn rounded-3 px-4 text-white"
              style="background: linear-gradient(90deg, #004F80, #0078AA);">
              <i class="fas fa-upload me-2"></i> Upload
            </button>
          </div>

          <!-- Modern Progress Bar -->
          <div class="mt-4 d-none" id="uploadProgress">
            <div class="progress rounded-pill" style="height: 12px;">
              <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                role="progressbar" style="width: 0%">0%</div>
            </div>
            <div id="uploadStatus" class="mt-2 text-center small fw-semibold text-muted"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const fields = ['documents', 'folder_id', 'category_id'];

    fields.forEach(id => {
        const input = document.getElementById(id);
        const label = document.querySelector(`label[for="${id}"]`);
        const asterisk = label?.querySelector('span.text-danger');

        input.addEventListener('change', () => {
            if ((input.type === 'file' && input.files.length > 0) || 
                (input.type !== 'file' && input.value.trim() !== '')) {
                asterisk?.classList.add('d-none');
            } else {
                asterisk?.classList.remove('d-none');
            }
        });
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");

    if (status === "success") {
        Swal.fire({
            icon: "success",
            title: "Upload Successful",
            text: "Your document has been uploaded successfully!",
            timer: 2000,
            showConfirmButton: false
        });
    } else if (status === "error") {
        Swal.fire({
            icon: "error",
            title: "Upload Failed",
            text: "There was a problem uploading your document.",
        });
    }
});
</script>
