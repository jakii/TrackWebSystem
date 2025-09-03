<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
requireAuth();

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $db->prepare("
    SELECT d.*, 
           c.name AS category_name, 
           f.name AS folder_name
    FROM documents d
    LEFT JOIN categories c ON d.category_id = c.id
    LEFT JOIN folders f ON d.folder_id = f.id
    WHERE d.uploaded_by = ? AND d.is_deleted = 1
    ORDER BY d.deleted_at DESC
");
$stmt->execute([$user_id]);
$trash_documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    background: linear-gradient(90deg, #2AB7CA 0%, #004F80 100%);
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    padding: 6px 18px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,79,128,0.08);
    transition: background 0.2s, box-shadow 0.2s;
  }
  .btn-restore:hover {
    background: linear-gradient(90deg, #004F80 0%, #2AB7CA 100%);
    color: #FFD166;
    box-shadow: 0 6px 24px rgba(0,79,128,0.18);
  }
  .btn-delete {
    background: linear-gradient(90deg, #FFD166 0%, #FF4F4F 100%);
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    padding: 6px 18px;
    border: none;
    box-shadow: 0 2px 8px rgba(255,79,79,0.08);
    transition: background 0.2s, box-shadow 0.2s;
  }
  .btn-delete:hover {
    background: linear-gradient(90deg, #FF4F4F 0%, #FFD166 100%);
    color: #fff;
    box-shadow: 0 6px 24px rgba(255,79,79,0.18);
  }
</style>
<div class="container fade-in delay-1">
  <div class="trash-card">
    <h4><i class="fas fa-trash-alt me-2 text-danger"></i>Trash Bin</h4>
    <p class="text-muted">Documents you deleted will appear here. You can restore or permanently delete them.</p>
    <hr>
    <?php if (empty($trash_documents)): ?>
      <div class="alert alert-info">Trash is empty.</div>
    <?php else: ?>
      <div class="table-responsive fade-in delay-2">
        <table class="table trash-table align-middle mb-0">
          <thead>
            <tr>
              <th>Document Name</th>
              <th>Category</th>
              <th>Folder</th>
              <th>Deleted At</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($trash_documents as $doc): ?>
              <tr>
                <td><?= htmlspecialchars($doc['title']) ?></td>
                <td><?= htmlspecialchars($doc['category_name']) ?></td>
                <td><?= htmlspecialchars($doc['folder_name']) ?></td>
                <td><?= date('M d, Y h:i A', strtotime($doc['deleted_at'])) ?></td>
                <td class="text-center">
                  <form action="restore_document.php" method="POST" class="d-inline restore-form">
                    <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-restore">Restore</button>
                  </form>
                  <form action="permanent_delete_document.php" method="POST" class="d-inline delete-form">
                    <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-delete">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.querySelectorAll('.restore-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();  // prevent immediate submit
      Swal.fire({
        title: 'Restore Document?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          form.submit(); // submit form after confirmation
        }
      });
    });
  });

  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();  // prevent immediate submit
      Swal.fire({
        title: 'Permanently delete document?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          form.submit(); // submit form after confirmation
        }
      });
    });
  });
</script>
