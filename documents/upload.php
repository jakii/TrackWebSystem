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
        echo "<script>window.location.href='upload.php?status=success';</script>";
        exit;
    } else {
        echo "<script>window.location.href='upload.php?status=error';</script>";
        exit;
    }
}
?>
<div class="row mb-4 mt-3 justify-content-center">
    <script src="../assets/js/upload.js"></script>
    <div class="col-md-8">
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header" style="background-color: #004F80; color: white;">
                <h4 class="mb-0">
                    <i class="fas fa-upload me-2"></i> Upload Document
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Files</label>
                        <div id="drop-zone" 
                             class="border border-2 border-dashed rounded-4 p-5 text-center bg-light"
                             style="cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                            <p class="mb-1 fw-semibold">Drag & drop files here</p>
                            <small class="text-muted">or click to select files</small>
                        </div>
                        <input type="file" class="form-control d-none" id="documents" name="documents[]" multiple>
                        <div id="file-list" class="mt-3 small text-muted"></div>
                    </div>
                
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="folder_id" class="form-label fw-bold">Destination Folder</label>
                        <select class="form-select" id="folder_id" name="folder_id" required>
                            <option value="">Select a folder</option>
                            <?php foreach ($folders as $folder): ?>
                            <option value="<?php echo $folder['id']; ?>" 
                                <?php echo (isset($folder_id) && $folder_id == $folder['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(getFolderPath($folder['id'], $foldersById)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo (isset($category_id) && $category_id == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?php echo htmlspecialchars($tags ?? ''); ?>" 
                               placeholder="Enter tags separated by commas">
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_public" name="is_public" 
                               <?php echo (isset($is_public) && $is_public) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_public">
                            Make this document public (visible to all users)
                        </label>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?php echo $current_folder_id ? 'browse.php?folder=' . $current_folder_id : '../dashboard.php'; ?>" 
                           class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>
                        <button type="submit" name="upload" class="btn" style="background-color: #004F80; color: white;">
                            <i class="fas fa-upload me-2"></i> Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
