<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../api/api_trash.php';
requireAuth();
?>

<style>
  .trash-card {
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,79,128,0.08);
    border: none;
    background: #fff;
    padding: 32px 28px;
    margin-top: 24px;
  }
  .trash-table th, .trash-table td {
    vertical-align: middle !important;
    font-size: 1rem;
  }
  .trash-table th {
    background: #f4f6f8;
    color: #004F80;
    font-weight: 600;
    border-top: none;
  }
  .trash-table tr {
    transition: box-shadow 0.2s;
  }
  .trash-table tr:hover {
    box-shadow: 0 2px 12px rgba(0,79,128,0.10);
    background: #e0eafc;
  }
  .btn-restore {
    background-color: #004F80;
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    padding: 6px 18px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,79,128,0.08);
    transition: background 0.2s, box-shadow 0.2s;
  }
  .btn-restore:hover {
    background-color: #004F80;
    color: #fff;
    box-shadow: 0 6px 24px #004f802e;
    transform: translateY(-2px) scale(1.04);
  }
  .btn-delete {
    background:  #FFD166;
    color: #2F4858;
    font-weight: 600;
    border-radius: 10px;
    padding: 6px 18px;
    border: none;
    box-shadow: 0 2px 8px rgba(255,79,79,0.08);
    transition: background 0.2s, box-shadow 0.2s;
  }
  .btn-delete:hover {
    background: #FFD166;
    color: #2F4858;
    box-shadow: 0 6px 24px #004f802e;
    transform: translateY(-2px) scale(1.04);
  }
</style>
<div class="container">
  <div class="trash-card">
    <h4><i class="fas fa-trash-alt me-2 text-danger"></i>Trash Bin</h4>
    <p class="text-muted">Documents you deleted will appear here. You can restore or permanently delete them.</p>
    <hr>
<?php if (empty($trash_documents)): ?>
  <div class="alert alert-info">Trash is empty.</div>
<?php else: ?>
  <form id="bulkActionForm" method="POST">
    <div class="d-flex justify-content-end mb-2">
      <button type="submit" name="bulk_restore" class="btn btn-restore me-2">Restore</button>
      <button type="submit" name="bulk_delete" class="btn btn-delete">Delete</button>
    </div>
<div class="table-responsive fade-in delay-2">
  <table class="table trash-table align-middle mb-0">
    <thead>
      <tr>
        <th><input type="checkbox" id="selectAll"></th>
        <th>Document Name</th>
        <th>Category</th>
        <th>Deleted At</th>
        <?php if ($is_admin): ?>
          <th>Owner</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($trash_documents as $doc): ?>
        <tr>
          <td>
            <input type="checkbox" name="selected_docs[]" value="<?= $doc['id'] ?>" class="doc-checkbox">
          </td>
          <td>
            <div class="d-flex align-items-center">
              <i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)) ?> me-2 text-secondary"></i>
              <span><?= htmlspecialchars($doc['title']) ?></span>
            </div>
          </td>
          <td><?= htmlspecialchars($doc['category_name']) ?></td>
          <td><?= date('M d, Y h:i A', strtotime($doc['deleted_at'])) ?></td>
          <?php if ($is_admin): ?>
            <td>
              <div class="d-flex align-items-center">
                <?php 
                  $initial = strtoupper(substr($doc['owner_name'], 0, 1)); 
                ?>
                <div style="width: 30px; height: 30px; border-radius: 50%;
                            background: linear-gradient(135deg, #004F80, #0073b6);
                            display: flex; align-items: center; justify-content: center;
                            color: white; font-weight: 600; font-size: 0.8rem; margin-right: 8px;">
                  <?= $initial ?>
                </div>
                <span><?= htmlspecialchars($doc['owner_name']) ?></span>
              </div>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

  </form>
<?php endif; ?>
<script>
  document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.doc-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });

  document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const selected = document.querySelectorAll('.doc-checkbox:checked');
    if (selected.length === 0) {
      Swal.fire('No documents selected', 'Please select at least one document.', 'warning');
      return;
    }

    let action = e.submitter.name === "bulk_restore" ? "restore" : "delete";
    let confirmText = action === "restore" ? "Yes, restore them!" : "Yes, delete them!";
    let titleText = action === "restore" ? "Restore selected documents?" : "Delete selected documents permanently?";

    Swal.fire({
      title: titleText,
      icon: action === "restore" ? "question" : "warning",
      showCancelButton: true,
      confirmButtonText: confirmText,
      cancelButtonText: "Cancel"
    }).then(result => {
      if (result.isConfirmed) {
        this.action = action === "restore" ? "bulk_restore.php" : "bulk_delete.php";
        this.submit();
      }
    });
  });
</script>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.querySelectorAll('.restore-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Restore Document?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Permanently delete document?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
