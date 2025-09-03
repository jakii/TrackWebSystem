<?php
require_once '../includes/header.php';
include '../api/api_search.php';
// Get search query from GET
$search = trim($_GET['search'] ?? '');
?>
<div class="row fade-in delay-1">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-search me-2" style="color: #2AB7CA;"></i>Search Results
        </h1>
        <hr>
    </div>
</div>
<div class="card fade-in delay-2 shadow rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom rounded-top-4">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color: #2AB7CA;"></i> Results for "<?php echo htmlspecialchars($search); ?>"
            </h5>
            <small class="text-muted">Showing <?php echo number_format($total_results); ?> document(s)</small>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($documents)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No documents found</h5>
            <p class="text-muted">
                <?php if ($search): ?>
                Try adjusting your search criteria or use advanced search.
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
                            <span class="badge" style="background-color: <?php echo $doc['category_color']; ?>;">
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
                                    <li><a class="dropdown-item" href="preview.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="Preview"><i class="fas fa-eye me-2"></i> Preview</a></li>
                                    <li><a class="dropdown-item" href="download.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="Download"><i class="fas fa-download me-2"></i> Download</a></li>
                                    <li><a class="dropdown-item" href="view.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="View Details"><i class="fas fa-info-circle me-2"></i> View Details</a></li>
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id']): ?>
                                        <li><a class="dropdown-item" href="share.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="Share"><i class="fas fa-share me-2"></i> Share</a></li>
                                    <?php endif; ?>
                                    <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="delete.php?id=<?= $doc['id'] ?>" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this document?');"><i class="fas fa-trash me-2"></i> Delete</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
        <?php endif; ?>
    </div>
</div>
