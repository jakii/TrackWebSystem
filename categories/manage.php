<?php
include '../api/api_manage.php';
require_once '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
  <div>
    <h3 class="fw-bold mb-0">
      <i class="fas fa-tags me-2" style="color:#004F80;"></i>Manage Categories
    </h3>
    <p class="text-muted mb-0">Create and organize document categories.</p>
  </div>
  <div class="d-flex gap-2">
    <!-- New Category Button -->
    <button type="button" class="btn rounded-pill" style="background-color:#004F80;color:white;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
      <i class="fas fa-plus-circle me-2"></i>New Category
    </button>

    <a href="javascript:void(0);" 
        onclick="if (document.referrer !== '') { window.location.href='<?php echo BASE_URL; ?>settings/system.php'; }"
        class="btn btn-secondary rounded-pill">
      <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
  </div>
</div>

<hr>

<!-- Alerts -->
<?php if (!empty($success)): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php elseif (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Category Name</label>
          <input type="text" class="form-control" name="category_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="category_description" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Color</label>
          <input type="color" class="form-control form-control-color" name="category_color" value="#004F80">
        </div>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="create_category" class="btn btn-primary">Create</button>
      </div>
    </form>
  </div>
</div>

<!-- Categories Display -->
<div class="card shadow rounded-4 border-0 fade-in delay-1">
  <div class="card-body">
    <?php if (empty($categories)): ?>
      <div class="text-center py-5">
        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No categories created yet</h5>
        <p class="text-muted">Create your first category to organize documents.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive slide-in-up">
        <table class="table table-hover align-middle text-start">
          <thead class="table-light">
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
                <a href="view_category.php?id=<?= $category['id']; ?>" 
                   class="badge rounded-pill px-3 py-2 text-decoration-none" 
                   style="background-color:<?= htmlspecialchars($category['color']); ?>; color:white;">
                  <?= htmlspecialchars($category['name']); ?>
                </a>
              </td>
              <td><?= htmlspecialchars($category['description'] ?: 'No description'); ?></td>
              <td><span class="badge bg-warning text-dark text-center"><?= number_format($category['document_count']); ?></span></td>
              <td><?= htmlspecialchars($category['creator_name'] ?: 'Unknown'); ?></td>
              <td>
                <button type="button" 
                  class="btn btn-sm btn-outline-primary me-1"
                  data-bs-toggle="modal" 
                  data-bs-target="#editCategoryModal"
                  data-id="<?= $category['id']; ?>"
                  data-name="<?= htmlspecialchars($category['name']); ?>"
                  data-description="<?= htmlspecialchars($category['description']); ?>"
                  data-color="<?= htmlspecialchars($category['color']); ?>">
                  <i class="fas fa-edit"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="category_id" id="edit_category_id">
        <div class="mb-3">
          <label class="form-label">Category Name</label>
          <input type="text" class="form-control" name="category_name" id="edit_category_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="category_description" id="edit_category_description" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Color</label>
          <input type="color" class="form-control form-control-color" name="category_color" id="edit_category_color" value="#004F80">
        </div>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="edit_category" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const editModal = document.getElementById('editCategoryModal');

  editModal.addEventListener('show.bs.modal', (event) => {
    const button = event.relatedTarget;
    if (!button) return;

    // Get data attributes
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name') || '';
    const description = button.getAttribute('data-description') || '';
    const color = button.getAttribute('data-color') || '#004F80';

    // Populate modal inputs
    editModal.querySelector('#edit_category_id').value = id;
    editModal.querySelector('#edit_category_name').value = name;
    editModal.querySelector('#edit_category_description').value = description;
    editModal.querySelector('#edit_category_color').value = color;
  });

  // Optional: clear fields when modal is hidden
  editModal.addEventListener('hidden.bs.modal', () => {
    editModal.querySelector('form').reset();
  });
});
</script>


<style>
.fade-in { animation: fadeIn 0.3s ease-in-out; }
.slide-in-up { animation: slideInUp 0.3s ease; }

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
