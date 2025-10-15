<?php
require_once '../config/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_categories.php?error=Invalid+category");
    exit;
}

$category_id = intval($_GET['id']);

// Fetch category info
$stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: manage_categories.php?error=Category+not+found");
    exit;
}

// Fetch all documents under this category
$query = "
    SELECT d.id, d.title, d.created_at, u.full_name AS uploader
    FROM documents d
    LEFT JOIN users u ON d.uploaded_by = u.id
    WHERE d.category_id = ?
    ORDER BY d.created_at DESC
";
$stmt = $db->prepare($query);
$stmt->execute([$category_id]);
$documents = $stmt->fetchAll();
?>

<div class="container py-4">
  <a href="manage.php" class="btn btn-secondary rounded-pill mb-3">
    <i class="fas fa-arrow-left me-2"></i>Back to Categories
  </a>

  <h3 class="fw-bold mb-2">
    <i class="fas fa-folder-open me-2" style="color:<?= htmlspecialchars($category['color']); ?>"></i>
    <?= htmlspecialchars($category['name']); ?> Documents
  </h3>
  <p class="text-muted"><?= htmlspecialchars($category['description'] ?: 'No description available.'); ?></p>
  <hr>

  <?php if (empty($documents)): ?>
    <div class="alert alert-info text-center py-4">
      <i class="fas fa-info-circle me-2"></i>No documents found in this category.
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Uploaded By</th>
            <th>Date Uploaded</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($documents as $doc): ?>
            <tr>
              <td><?= htmlspecialchars($doc['title']); ?></td>
              <td><?= htmlspecialchars($doc['uploader'] ?: 'Unknown'); ?></td>
              <td><?= htmlspecialchars(date('M d, Y h:i A', strtotime($doc['created_at']))); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
