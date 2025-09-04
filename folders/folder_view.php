<?php
include '../api/api_folder_view.php';
require_once '../includes/header.php';
?>
<!-- Main Page -->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12 fade-in delay-1">
            <!-- Header -->
            <div>
                <div class="d-flex w-100 justify-content-between align-items-center gap-3">
                    <h2 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-folder-open me-2" style="color: #004F80;"></i>
                        <?php echo $current_folder ? htmlspecialchars($current_folder['name']) : 'Root Folders'; ?>
                    </h2>
                    <button type="button" class="btn rounded-pill px-4 ms-auto" style="background-color: #004F80; color: white; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="fas fa-folder-plus me-2" style="color:white;"></i> New Folder
                    </button>
                </div>
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item">
                        <a href="folder_view.php"><i class="fas fa-home" style="color: #004F80;"></i> Root</a>
                    </li>
                    <?php foreach ($breadcrumb as $crumb): ?>
                        <li class="breadcrumb-item">
                            <a href="folder_view.php?folder=<?php echo $crumb['id']; ?>">
                                <?php echo htmlspecialchars($crumb['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <!-- Folder Display -->
            <?php if (!empty($subfolders)): ?>
            <div class="mb-4 card shadow rounded-4 border-0 bg-white">
                
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Documents</th>
                            <th>Subfolders</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subfolders as $folder): ?>
                        <tr ondblclick="window.location.href='folder_view.php?folder=<?php echo $folder['id']; ?>'" style="cursor: pointer;">
                            <td>
                                <i class="fas fa-folder" style="color: <?php echo htmlspecialchars($folder['color']); ?>; font-size: 1.5rem;"></i>
                            </td>
                            <td class="fw-semibold text-primary"><?php echo htmlspecialchars($folder['name']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($folder['description']); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $folder['document_count']; ?></span></td>
                            <td><span class="badge bg-warning text-dark"><?php echo $folder['subfolder_count']; ?></span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" id="folderActions<?= $folder['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                                        <i class="fas fa-ellipsis-v" style="font-size: 1.2rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="folderActions<?= $folder['id'] ?>">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="event.stopPropagation(); document.getElementById('edit_id').value='<?php echo $folder['id']; ?>'; document.getElementById('edit_name').value='<?php echo htmlspecialchars($folder['name'], ENT_QUOTES); ?>'; document.getElementById('edit_description').value='<?php echo htmlspecialchars($folder['description'], ENT_QUOTES); ?>'; document.getElementById('edit_color').value='<?php echo $folder['color']; ?>'; var modal = new bootstrap.Modal(document.getElementById('editFolderModal')); modal.show();">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="event.stopPropagation(); document.getElementById('delete_id').value='<?php echo $folder['id']; ?>'; var modal = new bootstrap.Modal(document.getElementById('deleteFolderModal')); modal.show();">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- Document Section -->
            <?php if (!empty($documents)): ?>
            <div class="mb-4 card shadow rounded-4 border-0 bg-white">
                <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>Title</th>
                                <th>Size</th>
                                <th>Uploader</th>
                                <th>Date Uploaded</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td>
                                    <i class="<?php echo getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?>" style="font-size: 1.3rem; color: #2AB7CA;"></i>
                                </td>
                                <td class="fw-semibold text-dark"><?php echo htmlspecialchars($doc['title']); ?></td>
                                <td><span class="badge bg-secondary text-light"><?php echo formatFileSize($doc['file_size']); ?></span></td>
                                <td><?php echo htmlspecialchars($doc['uploader_name']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($doc['created_at'])); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="docActions<?= $doc['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                                            <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="docActions<?= $doc['id'] ?>">
                                            <li>
                                                <a class="dropdown-item" href="../documents/preview.php?id=<?php echo $doc['id']; ?>">
                                                    <i class="fas fa-eye me-2"></i>Preview
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="../documents/download.php?id=<?php echo $doc['id']; ?>">
                                                    <i class="fas fa-download me-2"></i>Download
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="../documents/share.php?id=<?php echo $doc['id']; ?>">
                                                    <i class="fas fa-share me-2"></i>Share
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                </table>     
             </div>
            <?php endif; ?>

            <!-- Empty State -->
            <?php if (empty($subfolders) && empty($documents)): ?>
            <div class="text-center py-5 card shadow rounded-4 border-0 bg-white">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">This folder is empty</h4>
                <p class="text-muted">Create a new folder to get started.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="../api/api_folder_create.php">
                <input type="hidden" name="parent_folder_id" value="<?php echo $current_folder_id ?? 0; ?>">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-folder-plus me-2"></i>Create New Folder</h5>
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
                        <input type="color" class="form-control form-control-color" id="folder_color" name="folder_color" value="#004f80">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #004F80; color: white;"><i class="fas fa-folder-plus me-2"></i>Create Folder</button>
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
