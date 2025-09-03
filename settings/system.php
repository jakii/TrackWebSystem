<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAuth();
requireAdmin();

$errors = [];
$success = false;

// Fetch current settings
$stmt = $db->query("SELECT * FROM system_settings");
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Default values if empty
$site_name = $settings['site_name'] ?? '';
$site_email = $settings['site_email'] ?? '';
$items_per_page = $settings['items_per_page'] ?? 20;

// Handle form submission (optional: can be via API)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $site_name = trim($_POST['site_name'] ?? '');
    $site_email = trim($_POST['site_email'] ?? '');
    $items_per_page = intval($_POST['items_per_page'] ?? 20);

    if ($site_name === '') {
        $errors[] = "Site name is required.";
    }
    if (!filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if ($items_per_page <= 0) {
        $errors[] = "Items per page must be positive.";
    }

    if (empty($errors)) {
        // Save settings (you can do individual upserts)
        $updateStmt = $db->prepare("INSERT INTO system_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");

        $updateStmt->execute(['site_name', $site_name]);
        $updateStmt->execute(['site_email', $site_email]);
        $updateStmt->execute(['items_per_page', $items_per_page]);

        $success = true;
    }
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h4><i class="fas fa-cogs me-2" style="color: #004F80;"></i>System Settings</h4>
    <p class="text-muted">Configure your system-wide settings below.</p>

    <?php if ($success): ?>
      <div class="alert alert-success">Settings updated successfully.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="system.php" novalidate>
      <div class="mb-3">
        <label for="siteName" class="form-label">Site Name</label>
        <input type="text" class="form-control" id="siteName" name="site_name" value="<?= htmlspecialchars($site_name) ?>" required>
      </div>
      <div class="mb-3">
        <label for="siteEmail" class="form-label">Admin Email</label>
        <input type="email" class="form-control" id="siteEmail" name="site_email" value="<?= htmlspecialchars($site_email) ?>" required>
      </div>
      <div class="mb-3">
        <label for="itemsPerPage" class="form-label">Items Per Page</label>
        <input type="number" class="form-control" id="itemsPerPage" name="items_per_page" value="<?= (int)$items_per_page ?>" min="1" required>
      </div>
      <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
