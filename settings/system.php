<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAuth();
requireAdmin();
include '../includes/header.php';
?>

<div class="container mt-4">
  <div class="card shadow rounded-4 border-0 ">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h4 class="mb-0">
        <i class="fas fa-gear me-2" style="color:#004F80;"></i>System Settings
      </h4>

      <!-- Go Back Button -->
      <a href="javascript:void(0);" 
         onclick="if (document.referrer !== '') { window.history.back(); } else { window.location.href='<?php echo BASE_URL; ?>dashboard.php'; }" 
         class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Go Back
      </a>
    </div>

    <div class="card-body">
      <div class="row mt-4 g-3">
        <div class="col-md-3 col-sm-6">
          <a href="<?= BASE_URL ?>categories/manage.php" class="text-decoration-none text-dark">
            <div class="card p-3 shadow-sm rounded-4 text-center h-100 hover-shadow">
              <i class="fas fa-layer-group fa-2x mb-2" style="color:#004F80;"></i>
              <h6 class="fw-semibold mb-0">Manage Categories</h6>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="<?= BASE_URL ?>users/manage_user.php" class="text-decoration-none text-dark">
            <div class="card p-3 shadow-sm rounded-4 text-center h-100 hover-shadow">
              <i class="fas fa-user-gear fa-2x mb-2" style="color:#004F80;"></i>
              <h6 class="fw-semibold mb-0">Manage Users</h6>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="<?= BASE_URL ?>logs/audit.php" class="text-decoration-none text-dark">
            <div class="card p-3 shadow-sm rounded-4 text-center h-100 hover-shadow">
              <i class="fas fa-clipboard-check fa-2x mb-2" style="color:#004F80;"></i>
              <h6 class="fw-semibold mb-0">Activity Logs</h6>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="<?= BASE_URL ?>backup_recovery/backup.php" class="text-decoration-none text-dark">
            <div class="card p-3 shadow-sm rounded-4 text-center h-100 hover-shadow">
              <i class="fas fa-database fa-2x mb-2" style="color:#004F80;"></i>
              <h6 class="fw-semibold mb-0">Backup & Recovery</h6>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
