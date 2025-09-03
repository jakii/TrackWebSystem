<?php
require_once '../includes/header.php';
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
?>
<div class="row mb-4 mt-3 justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-upload me-2" style="color: #004F80;"></i>Upload Document
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="document" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="document" name="document" required>
                        <div class="form-text">
                            Maximum file size: <?php echo formatFileSize(MAX_FILE_SIZE); ?>. 
                            Allowed types: <?php echo implode(', ', ALLOWED_EXTENSIONS); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Document Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="folder_id" class="form-label">Destination Folder</label>
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
                        <label for="category_id" class="form-label">Category</label>
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
                        <label for="tags" class="form-label">Tags</label>
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
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $current_folder_id ? 'browse.php?folder=' . $current_folder_id : '../dashboard.php'; ?>" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" name="upload" class="btn" style ="background-color: #004F80; color: white;">
                            <i class="fas fa-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>