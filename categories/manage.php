<?php
include '../api/api_manage.php';
require_once '../includes/header.php';
?>
<div class="d-flex justify-content-end gap-3 mt-2 fade-in delay-1">
    <button type="button" class="btn rounded-pill" style="background-color: #004F80; color: white;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fas fa-plus-circle me-2"></i>New Category
    </button>
    <a href="../dashboard.php" class="btn btn-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>
<hr>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="category_create.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="category_name" required>
                </div>
                <div class="mb-3">
                    <label for="categoryDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="categoryDescription" name="category_description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="categoryColor" class="form-label">Color</label>
                    <input type="color" class="form-control form-control-color" id="categoryColor" name="category_color" value="#004F80" title="Choose category color">
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="create_category" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<div class="row fade-in delay-2">
    <div>
        <div class="card shadow rounded-4 border-0">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-tags me-2" style="color: #004F80;"></i>Manage Categories
                </h4>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No categories created yet</h5>
                        <p class="text-muted">Create your first category to organize documents.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Documents</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td>
                                            <span class="badge me-2" style="background-color: <?php echo htmlspecialchars($category['color']); ?>">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($category['description'] ?: 'No description'); ?></td>
                                        <td>
                                            <span class="badge" style="background-color: #FFD166; color: #2F4858;"><?php echo number_format($category['document_count']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($category['creator_name'] ?: 'Unknown'); ?></td>
                                        <td>
                                            <?php if ($category['document_count'] == 0): ?>
                                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete the category &quot;<?php echo htmlspecialchars($category['name']); ?>&quot;?');">
                                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                    <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted" title="Cannot delete category with documents">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
