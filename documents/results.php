<?php
require_once '../includes/header.php';
include '../api/api_search.php';

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
                                    <i class="fas fa-folder text-warning me-2"></i>
                                <?php else: ?>
                                    <i class="<?php echo getFileIcon(pathinfo($item['file_type'] ?? '', PATHINFO_EXTENSION)); ?> me-2"></i>
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
                                <span class="badge bg-secondary">Uncategorized</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php 
                            echo ($item['type'] === 'folder') 
                                ? '-' 
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
                            <?php if ($item['type'] === 'document'): ?>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" id="actions<?= $item['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actions<?= $item['id'] ?>">
                                        <li><a class="dropdown-item" href="preview.php?id=<?= $item['id'] ?>"><i class="fas fa-eye me-2"></i> Preview</a></li>
                                        <li><a class="dropdown-item" href="download.php?id=<?= $item['id'] ?>"><i class="fas fa-download me-2"></i> Download</a></li>
                                        <li><a class="dropdown-item" href="view.php?id=<?= $item['id'] ?>"><i class="fas fa-info-circle me-2"></i> View Details</a></li>
                                        <?php if ($item['uploaded_by'] == $_SESSION['user_id']): ?>
                                            <li><a class="dropdown-item" href="share.php?id=<?= $item['id'] ?>"><i class="fas fa-share me-2"></i> Share</a></li>
                                        <?php endif; ?>

                                        <?php if ($item['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="delete.php?id=<?= $item['id'] ?>&type=<?= $item['type'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
                                                   <i class="fas fa-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <script>
            function handleRowClick(type, id) {
                if (type === 'folder') {
                    window.location.href = 'browse.php?folder=' + id;
                } else {
                    window.location.href = 'preview.php?id=' + id;
                }
            }
            </script>
        <?php endif; ?>
    </div>
</div>
