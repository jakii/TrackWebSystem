<?php
include '../api/api_browse.php';
include '../includes/header.php';
require_once '../api/delete_folder.php';
requireAuth();

$current_folder_id = $_GET['folder'] ?? null;
$view = $_GET['view'] ?? 'list';
?>

<div class="container-fluid">
    <!-- HEADER,BREADCRUMB -->
    <div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="h4 mb-0">
                <i class="fas fa-folder-open me-2" style="color: #004F80;"></i>
                <?php echo $current_folder ? htmlspecialchars($current_folder['name']) : 'All Documents'; ?>
            </h1>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="browse.php" style="text-decoration:none;">
                            <i class="fas fa-home" style="color:#004F80;margin-right:5px;"></i> Home
                        </a>
                    </li>
                    <?php foreach ($breadcrumb as $index => $crumb): ?>
                        <li class="breadcrumb-item <?php echo $index == count($breadcrumb) - 1 ? 'active' : ''; ?>">
                            <?php if ($index == count($breadcrumb) - 1): ?>
                                <?php echo htmlspecialchars($crumb['name']); ?>
                            <?php else: ?>
                                <a href="browse.php?folder=<?php echo $crumb['id']; ?>" style="text-decoration:none;color:black;">
                                    <?php echo htmlspecialchars($crumb['name']); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>

        <!-- TOOLBAR -->
        <div class="btn-toolbar gap-2">
            <div class="btn-group">
                <button class="btn btn-sm rounded-pill me-2" style="background-color:#004F80;color:white;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-1"></i> Upload
                </button>
                <?php if (isAdmin()): ?>
                    <button class="btn btn-sm rounded-pill me-2" style="background-color:#FFD166;color:#2F4858;" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="fas fa-folder-plus me-1"></i> New Folder
                    </button>
                <?php endif; ?>
                <button id="toggleViewBtn" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas <?php echo $view === 'grid' ? 'fa-list' : 'fa-th-large'; ?> me-1"></i>
                    <?php echo $view === 'grid' ? 'List View' : 'Grid View'; ?>
                </button>
            </div>
        </div>
    </div>
    <!-- Move Document Modal -->
        <div class="modal fade" id="moveDocumentModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="../api/api_move_document.php">
                        <input type="hidden" name="action" value="move_document">
                        <input type="hidden" name="current_folder_id" value="<?php echo $current_folder_id ?? ''; ?>">
                        <input type="hidden" name="document_id" id="move_document_id">

                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-folder me-2" style="color:#004F80;"></i> Move Document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Document to Move</label>
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-file me-2 text-muted"></i>
                                    <span id="move_document_name" class="fw-semibold"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Destination Folder</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="target_folder_id" id="root_folder" value="" checked>
                                        <label class="form-check-label fw-semibold" for="root_folder">
                                            <i class="fas fa-folder me-2" style="color:#004F80;"></i> Root Folder
                                        </label>
                                    </div>

                                    <?php
                                    //Recursive function to display folder tree
                                    function displayFolderTree($db, $parent_id = null, $level = 0, $exclude_folder_id = null) {
                                        $html = '';
                                        $query = "SELECT id, name, color FROM folders WHERE parent_id " . 
                                                 ($parent_id ? "= ?" : "IS NULL") . 
                                                 " AND id != ? ORDER BY sort_order, name";
                                        $stmt = $db->prepare($query);

                                        if ($parent_id) {
                                            $stmt->execute([$parent_id, $exclude_folder_id ?? 0]);
                                        } else {
                                            $stmt->execute([$exclude_folder_id ?? 0]);
                                        }

                                        $folders = $stmt->fetchAll();

                                        foreach ($folders as $folder) {
                                            $margin = $level * 25;
                                            $folder_color = $folder['color'] ?: '#004F80';
                                            $html .= '
                                            <div class="form-check mb-2" style="margin-left: ' . $margin . 'px;">
                                                <input class="form-check-input" type="radio" name="target_folder_id" id="folder_' . $folder['id'] . '" value="' . $folder['id'] . '">
                                                <label class="form-check-label" for="folder_' . $folder['id'] . '">
                                                    <i class="fas fa-folder me-2" style="color: ' . $folder_color . '"></i>
                                                    ' . htmlspecialchars($folder['name']) . '
                                                </label>
                                            </div>';

                                            $html .= displayFolderTree($db, $folder['id'], $level + 1, $exclude_folder_id);
                                        }

                                        return $html;
                                    }
                                
                                    echo displayFolderTree($db, null, 0, $current_folder_id);
                                    ?>
                                </div>
                            </div>
                        </div>
                                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn" style="background-color:#004F80;color:white;">
                                <i class="fas fa-arrows-alt me-2"></i> Move Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ================== UPLOAD MODAL ================== -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <script src="../assets/js/upload.js"></script>
                <div class="modal-content shadow-lg rounded-4 border-0">
                    <div class="modal-header" style="background-color:#004F80;color:white;">
                        <h5 class="modal-title" id="uploadModalLabel">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Upload Document
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../api/api_browse.php" enctype="multipart/form-data">
                            <input type="hidden" name="current_folder_id" value="<?php echo $current_folder_id ?? ''; ?>">
                                        
                            <div class="mb-4">
                                <label for="documents" class="form-label fw-bold">
                                    Upload Files <span class="text-danger" style="font-size:1.5em;">*</span>
                                </label>
                                <div id="drop-zone" class="border border-2 border-dashed rounded-4 p-5 text-center bg-light" style="cursor:pointer;">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                                    <p class="mb-1 fw-semibold">Drag & drop files here</p>
                                    <small class="text-muted">or click to select files</small>
                                </div>
                                <input type="file" class="form-control d-none" id="documents" name="documents[]" multiple required>
                                <div id="file-list" class="mt-3 small text-muted"></div>
                            </div>
                                        
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                                        
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">
                                    Category <span class="text-danger me-4" style="font-size:1.5em;">*</span>
                                </label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                                    
                            <div class="mb-3">
                                <label for="tags" class="form-label fw-bold">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter tags separated by commas">
                            </div>
                                    
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public">
                                <label class="form-check-label" for="is_public">Make this document public</label>
                            </div>
                                    
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="upload" class="btn" style="background-color:#004F80;color:white;">
                                    <i class="fas fa-upload me-2"></i> Upload
                                </button>
                            </div>
                            <script src="../assets/js/upload_preview.js"></script>
                        </form>
                    </div>
                    <Script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const fileInput = document.getElementById('documents');
                            const categorySelect = document.getElementById('category_id');
                        
                            const fileAsterisk = document.querySelector('label[for="documents"] span.text-danger');
                            const categoryAsterisk = document.querySelector('label[for="category_id"] span.text-danger');
                        
                            fileInput.addEventListener('change', () => {
                                if (fileInput.files.length > 0) {
                                    fileAsterisk?.classList.add('d-none');
                                } else {
                                    fileAsterisk?.classList.remove('d-none');
                                }
                            });
                        
                            categorySelect.addEventListener('change', () => {
                                if (categorySelect.value) {
                                    categoryAsterisk?.classList.add('d-none');
                                } else {
                                    categoryAsterisk?.classList.remove('d-none');
                                }
                            });
                        });
                    </Script>
                </div>
            </div>
        </div>


    <!-- ================== FOLDER MODALS ================== -->
    <!-- Create Folder -->
    <div class="modal fade" id="createFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="create_folder">
                    <input type="hidden" name="current_folder_id" value="<?php echo $current_folder_id ?? ''; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-folder-plus me-2" style="color:#004F80;"></i> Create Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Folder Name</label>
                        <input type="text" class="form-control mb-3" name="folder_name" required>
                        <label class="form-label">Description</label>
                        <textarea class="form-control mb-3" name="folder_description" rows="2"></textarea>
                        <label class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" name="folder_color" value="#004F80">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background-color:#004F80;color:white;">
                            <i class="fas fa-folder-plus me-2"></i>Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Folder -->
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
                        <label>Folder Name</label>
                        <input type="text" class="form-control mb-3" name="folder_name" id="edit_name" required>
                        <label>Description</label>
                        <textarea class="form-control mb-3" name="folder_description" id="edit_description" rows="2"></textarea>
                        <label>Color</label>
                        <input type="color" class="form-control form-control-color" name="folder_color" id="edit_color">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background-color:#004F80;color:white;">
                            <i class="fas fa-save me-2"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Folder -->
    <div class="modal fade" id="deleteFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../api/api_folder_delete.php">
                    <input type="hidden" name="folder_id" id="delete_id">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-3"></i>Delete Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Warning: Deleting this folder will also remove all its files and subfolders. Do you want to proceed?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- SEARCH BAR -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="folderSearch" class="form-control border-start-0 rounded-end-pill" placeholder="Search folder or document...">
            </div>
        </div>
    </div>

    <!-- =============== LIST VIEW =============== -->
    <div id="listView" class="<?php echo $view === 'grid' ? 'd-none' : ''; ?>">
        <?php if (!empty($subfolders) || !empty($documents)): ?>
            <div class="mt-3 shadow rounded-4 border-0">
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
                            <tr ondblclick="window.location.href='browse.php?folder=<?= $folder['id'] ?>'" style="cursor:pointer;">
                                <td><i class="fas fa-folder me-2" style="color:<?= $folder['color'] ?>"></i><?= htmlspecialchars($folder['name']) ?></td>
                                <td></td>
                                <td><?= htmlspecialchars($folder['owner'] ?? '—') ?></td>
                                <td><?= date('M j, Y', strtotime($folder['created_at'])) ?></td>
                                <td class="text-center">
                                <?php if (isAdmin()): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" style="border:none;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="
                                                    document.getElementById('edit_id').value='<?= $folder['id'] ?>';
                                                    document.getElementById('edit_name').value='<?= htmlspecialchars($folder['name'], ENT_QUOTES) ?>';
                                                    document.getElementById('edit_description').value='<?= htmlspecialchars($folder['description'], ENT_QUOTES) ?>';
                                                    document.getElementById('edit_color').value='<?= $folder['color'] ?>';
                                                    var modal=new bootstrap.Modal(document.getElementById('editFolderModal'));modal.show();">
                                                    <i class='fas fa-edit me-2'></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="
                                                    document.getElementById('delete_id').value='<?= $folder['id'] ?>';
                                                    var modal=new bootstrap.Modal(document.getElementById('deleteFolderModal'));modal.show();">
                                                    <i class='fas fa-trash me-2'></i>Delete
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
                                <td><i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)) ?> me-2"></i><?= htmlspecialchars($doc['title']) ?></td>
                                <td><?= formatFileSize($doc['file_size']) ?></td>
                                <td><?= htmlspecialchars($doc['uploader_name']) ?></td>
                                <td><?= date('M j, Y', strtotime($doc['created_at'])) ?></td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" style="border:none;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i> Preview</a></li>
                                            <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="
                                                        document.getElementById('move_document_id').value='<?= $doc['id'] ?>';
                                                        document.getElementById('move_document_name').textContent='<?= htmlspecialchars($doc['title'], ENT_QUOTES) ?>';
                                                        var modal=new bootstrap.Modal(document.getElementById('moveDocumentModal'));modal.show();">
                                                        <i class='fas fa-arrows-alt me-2'></i>Move
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item" href="archive.php?id=<?= $doc['id'] ?>"><i class="fas fa-archive me-2"></i>Archive</a></li>
                                                <li><a class="dropdown-item text-danger" href="delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
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
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">This folder is empty</h4>
            </div>
        <?php endif; ?>
    </div>

    <!-- =============== GRID VIEW =============== -->
    <div id="gridView" class="<?= $view === 'grid' ? '' : 'd-none' ?>">
        <div class="row g-3 mt-2">
            <?php foreach ($subfolders as $folder): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3 hover-scale"
                            style="cursor:pointer;"
                            ondblclick="window.location.href='browse.php?folder=<?= $folder['id'] ?>'">
                             <div class="d-flex justify-content-between align-items-start">
                                <i class="fas fa-folder fa-2x" style="color:<?= $folder['color'] ?>"></i>
                                <?php if (isAdmin()): ?>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" style="border:none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="
                                                document.getElementById('edit_id').value='<?= $folder['id'] ?>';
                                                document.getElementById('edit_name').value='<?= htmlspecialchars($folder['name'], ENT_QUOTES) ?>';
                                                document.getElementById('edit_description').value='<?= htmlspecialchars($folder['description'], ENT_QUOTES) ?>';
                                                document.getElementById('edit_color').value='<?= $folder['color'] ?>';
                                                var modal=new bootstrap.Modal(document.getElementById('editFolderModal'));modal.show();">
                                                <i class='fas fa-edit me-2'></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="
                                                document.getElementById('delete_id').value='<?= $folder['id'] ?>';
                                                var modal=new bootstrap.Modal(document.getElementById('deleteFolderModal'));modal.show();">
                                                <i class='fas fa-trash me-2'></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        <div class="mt-3">
                            <h6 class="fw-bold mb-1 text-truncate"><?= htmlspecialchars($folder['name']) ?></h6>
                            <small></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php foreach ($documents as $doc): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3 hover-scale">
                        <div class="d-flex justify-content-between align-items-start">
                            <i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)) ?> fa-2x text-secondary"></i>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" style="border:none;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i>Preview</a></li>
                                    <li><a class="dropdown-item" href="download.php?id=<?= $doc['id'] ?>"><i class="fas fa-download me-2"></i>Download</a></li>

                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="
                                                document.getElementById('move_document_id').value='<?= $doc['id'] ?>';
                                                document.getElementById('move_document_name').textContent='<?= htmlspecialchars($doc['title'], ENT_QUOTES) ?>';
                                                var modal=new bootstrap.Modal(document.getElementById('moveDocumentModal'));modal.show();">
                                                <i class='fas fa-arrows-alt me-2'></i>Move
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item" href="documents/archive.php?id=<?= $doc['id'] ?>"><i class="fas fa-archive me-2"></i>Archive</a></li>
                                        <li><a class="dropdown-item text-danger" href="documents/delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6 class="fw-bold mb-1 text-truncate"><?= htmlspecialchars($doc['title']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($doc['uploader_name']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ========== SCRIPT TO TOGGLE VIEW ========== -->
<script src="../assets/js/browse.js"></script>

