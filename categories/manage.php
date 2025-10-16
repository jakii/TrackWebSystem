<?php
include '../api/api_manage.php';
require_once '../includes/header.php';

// Default to table view unless user selected grid
$view = $_GET['view'] ?? 'table';
?>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
  <div>
    <h3 class="fw-bold mb-0">
      <i class="fas fa-tags me-2" style="color:#004F80;"></i>Manage Categories
    </h3>
    <p class="text-muted mb-0">Create and organize document categories.</p>
  </div>
  <div class="d-flex gap-2">
    <!-- Toggle View -->
    <div class="btn-group">
      <a href="?view=table" class="btn btn-outline-secondary <?= $view === 'table' ? 'active' : '' ?>" title="Table View">
        <i class="fas fa-list"></i>
      </a>
      <a href="?view=grid" class="btn btn-outline-secondary <?= $view === 'grid' ? 'active' : '' ?>" title="Grid View">
        <i class="fas fa-th"></i>
      </a>
    </div>

    <!-- New Category Button -->
    <button type="button" class="btn rounded-pill" style="background-color:#004F80;color:white;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
      <i class="fas fa-plus-circle me-2"></i>New Category
    </button>

    <a href="javascript:void(0);" 
        onclick="if (document.referrer !== '') { window.history.back(); } else { window.location.href='<?php echo BASE_URL; ?>dashboard.php'; }"
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

      <?php if ($view === 'table'): ?>
      <!-- Table View -->
      <div class="table-responsive slide-in-up">
        <table class="table table-hover align-middle text-center">
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
              <td><span class="badge bg-warning text-dark"><?= number_format($category['document_count']); ?></span></td>
              <td><?= htmlspecialchars($category['creator_name'] ?: 'Unknown'); ?></td>
              <td>
                <?php if ($category['document_count'] == 0): ?>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete category “<?= htmlspecialchars($category['name']); ?>”?');">
                    <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                    <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                <?php else: ?>
                  <i class="fas fa-lock text-muted" title="Cannot delete category with documents"></i>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php else: ?>
      <!-- Grid View -->
      <div class="row g-4 slide-in-up">
        <?php foreach ($categories as $category): ?>
          <div class="col-md-4 col-lg-3">
            <a href="view_category.php?id=<?= $category['id']; ?>" class="text-decoration-none text-dark">
              <div class="card shadow-sm border-0 rounded-4 h-100 text-center hover-shadow transition">
                <div class="card-body">
                  <span class="badge rounded-pill px-4 py-2 mb-2" style="background-color:<?= htmlspecialchars($category['color']); ?>">
                    <?= htmlspecialchars($category['name']); ?>
                  </span>
                  <p class="text-muted small mb-3"><?= htmlspecialchars($category['description'] ?: 'No description'); ?></p>
                  <span class="badge bg-warning text-dark px-3 py-2 mb-3"><?= number_format($category['document_count']); ?> Documents</span>
                  <p class="small text-muted mb-2"><i class="fas fa-user me-1"></i><?= htmlspecialchars($category['creator_name'] ?: 'Unknown'); ?></p>
                  <?php if ($category['document_count'] == 0): ?>
                    <form method="POST" onsubmit="return confirm('Delete category “<?= htmlspecialchars($category['name']); ?>”?');">
                      <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                      <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                      <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </form>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill" disabled>
                      <i class="fas fa-lock"></i> In Use
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</div>

<script>
  <?php if (!empty($success)): ?>
  setTimeout(() => window.location.href = '?view=<?= $view ?>', 1200);
  <?php endif; ?>
</script>

<style>
.fade-in { animation: fadeIn 0.3s ease-in-out; }
.slide-in-up { animation: slideInUp 0.3s ease; }
.hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
.transition { transition: all 0.25s ease; }

a .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
