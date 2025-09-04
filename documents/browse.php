<?php
include '../api/api_browse.php';
include '../includes/header.php';
include '../api/api_upload.php';
?>

<!--main content-->
<div class="container-fluid">
        <div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap fade-in delay-1">
            <div>
                <h1 class="h4 mb-0">
                    <i class="fas fa-folder-open me-2" style="color: #004F80;"></i>
                    <?php echo $current_folder ? htmlspecialchars($current_folder['name']) : 'All Documents'; ?>
                    <?php if (isAdmin()): ?>
                        <a href="../folders/folder_view.php" class="ms-2 text-decoration-none" title="Manage Folders">
                            <i class="fas fa-cog" style="color: #004F80;"></i>
                        </a>
                    <?php endif; ?>
                </h1>

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="browse.php"><i class="fas fa-home"style="color: #004F80;"></i> Home</a>
                        </li>
                        <?php foreach ($breadcrumb as $index => $crumb): ?>
                            <li class="breadcrumb-item <?php echo $index == count($breadcrumb) - 1 ? 'active' : ''; ?>">
                                <?php if ($index == count($breadcrumb) - 1): ?>
                                    <?php echo htmlspecialchars($crumb['name']); ?>
                                <?php else: ?>
                                    <a href="browse.php?folder=<?php echo $crumb['id']; ?>">
                                        <?php echo htmlspecialchars($crumb['name']); ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            </div>
            <div class="btn-toolbar gap-2">
                <div class="btn-group">
                    <button class="btn btn-sm rounded-pill me-2" style="background-color: #004F80; color: white;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-1"></i> Upload
                    </button>
                    <?php if (isAdmin()): ?>
                        <button class="btn btn-sm rounded-pill" style="background-color: #FFD166; color: #2F4858;" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="fas fa-folder-plus me-1"></i> New Folder
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">
                            <i class="fas fa-upload me-2" style="color: #004F80;"></i>Upload Document
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="document" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="document" name="document" required>
                            <div class="form-text">
                                Max size: <?php echo formatFileSize(MAX_FILE_SIZE); ?>.
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
                            <div class="form-text">Helps with organizing and searching documents.</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public"
                                   <?php echo (isset($is_public) && $is_public) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_public">Make this document public</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload" class="btn" style ="background-color: #004F80; color: white;">
                            <i class="fas fa-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div class="modal fade" id="createFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="create_folder">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-folder-plus me-2" style="color: #004F80;"></i>Create New Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="folder_name" class="form-label">Folder Name</label>
                            <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="folder_description" class="form-label">Description</label>
                            <textarea class="form-control" id="folder_description" name="folder_description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="folder_color" class="form-label">Folder Color</label>
                            <input type="color" class="form-control form-control-color" id="folder_color" name="folder_color" value="#004F80">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style ="background-color: #004F80; color: white;">
                            <i class="fas fa-folder-plus me-2"></i>Create Folder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Folder Modal -->
    <div class="modal fade" id="editFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../api/api_folder_edit.php">
                    <input type="hidden" name="folder_id" id="edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Folder Name</label>
                            <input type="text" class="form-control" name="folder_name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="folder_description" id="edit_description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Folder Color</label>
                            <input type="color" class="form-control form-control-color" name="folder_color" id="edit_color">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background-color: #004F80; color: white;"><i class="fas fa-save me-2"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                                    
    <!-- Delete Folder Modal -->
    <div class="modal fade" id="deleteFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../api/api_folder_delete.php">
                    <input type="hidden" name="folder_id" id="delete_id">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Delete Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this folder? This will also delete all its subfolders.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Table/List View -->
        <?php if (!empty($subfolders) || !empty($documents)): ?>
            <div class="fade-in delay-2 mt-3 shadow rounded-4 border-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Size</th>
                            <th>Owner</th>
                            <th>Modified</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subfolders as $folder): ?>
                        <tr class="folder-row" 
                            ondblclick="window.location.href='browse.php?folder=<?php echo $folder['id']; ?>'" 
                            style="cursor:pointer;">

                            <!-- Name -->
                            <td>
                                <i class="fas fa-folder me-2" style="color: <?php echo htmlspecialchars($folder['color']); ?>"></i>
                                <strong><?php echo htmlspecialchars($folder['name']); ?></strong>
                            </td>
                    
                            <!-- Counts in the Size column -->
                            <td>
                                <small class="text-muted">
                                    <?php
                                        $folders = $folder['subfolder_count'] ?? 0;
                                        $docs = $folder['document_count'] ?? 0;
                                        if ($folders || $docs) {
                                            echo "$folders " . ($folders == 1 ? "folder" : "folders");
                                            echo " • $docs " . ($docs == 1 ? "doc" : "docs");
                                        } else {
                                            echo "—";
                                        }
                                    ?>
                                </small>
                            </td>
                                    
                            <!-- Owner -->
                            <td>
                                <?php echo !empty($folder['owner']) ? htmlspecialchars($folder['owner']) : '—'; ?>
                            </td>
                                    
                            <!-- Modified -->
                            <td>
                                <?php echo !empty($folder['created_at']) ? date('M j, Y', strtotime($folder['created_at'])) : '—'; ?>
                            </td>
                                    
                            <!-- Actions -->
                            <td class="text-center">
                                <?php if (isAdmin()): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button" 
                                                id="folderActions<?= $folder['id'] ?>" 
                                                data-bs-toggle="dropdown" aria-expanded="false" 
                                                style="border: none;">
                                            <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: #2F4858;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="folderActions<?= $folder['id'] ?>">
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="event.stopPropagation(); 
                                                            document.getElementById('edit_id').value='<?php echo $folder['id']; ?>'; 
                                                            document.getElementById('edit_name').value='<?php echo htmlspecialchars($folder['name'], ENT_QUOTES); ?>'; 
                                                            document.getElementById('edit_description').value='<?php echo htmlspecialchars($folder['description'], ENT_QUOTES); ?>'; 
                                                            document.getElementById('edit_color').value='<?php echo $folder['color']; ?>'; 
                                                            var modal = new bootstrap.Modal(document.getElementById('editFolderModal')); 
                                                            modal.show();">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="event.stopPropagation(); 
                                                            document.getElementById('delete_id').value='<?php echo $folder['id']; ?>'; 
                                                            var modal = new bootstrap.Modal(document.getElementById('deleteFolderModal')); 
                                                            modal.show();">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td>
                            <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                            <?php echo htmlspecialchars($doc['title']); ?>
                            <?php if ($doc['is_shared_with_me']): ?>
                                <i class="fas fa-share text-warning ms-2" title="Shared with you"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatFileSize($doc['file_size']); ?></td>
                        <td><?php echo htmlspecialchars($doc['uploader_name']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($doc['created_at'])); ?></td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" id="docActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                                    <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="docActions<?= $doc['id'] ?>">
                                    <li><a class="dropdown-item" href="preview.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-eye me-2"></i> Preview</a></li>
                                    <?php if (!isset($doc['share_permission']) || $doc['share_permission'] === 'download' || $doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                        <li><a class="dropdown-item" href="download.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-download me-2"></i> Download</a></li>
                                    <?php endif; ?>
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                        <li><a class="dropdown-item" href="share.php?id=<?php echo $doc['id']; ?>"><i class="fas fa-share me-2"></i> Share</a></li>
                                    <?php endif; ?>
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                      <li><hr class="dropdown-divider"></li>
                                      <li>
                                        <a class="dropdown-item text-danger" 
                                           href="delete.php?id=<?= $doc['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
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
        </div>
    <?php else: ?>
        <div class="text-center py-5 fade-in delay-2">
            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">This folder is empty</h4>
            <p class="text-muted">Upload documents or create folders to get started.</p>
        </div>
    <?php endif; ?>
</div>