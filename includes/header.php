<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';

$user_full_name = $_SESSION['full_name'] ?? null;
$username_display = $user_full_name ? htmlspecialchars($user_full_name) : 'Guest';
$user_initial = $user_full_name ? strtoupper(substr($user_full_name, 0, 1)) : '?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="/TrackWeb/assets/css/animations.css">
  <link href="assets/css/custom.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/LOGO.png">
  <script>
    document.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    });
  </script>
  <style>
  html, body {
    height: 100%;
    margin: 0;
    overflow: hidden;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    background-color: #F4F6F8;
  }

  @media (max-width: 991.98px) {
    .topbar {
      left: 0;
      right: 0;
      top: 0;
      height: auto;
      flex-direction: column;
      align-items: stretch;
      padding: 0.5rem 0.5rem;
      border-radius: 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      position: static;
    }
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 220px;
      height: 100%;
      z-index: 1040;
      border-radius: 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.03);
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .sidebar.show {
      transform: translateX(0);
    }
    .main-content {
      margin-left: 0;
      margin-right: 0;
      margin-top: 70px;
      height: auto;
      padding: 12px;
      border-radius: 0;
    }
  }

  @media (max-width: 575.98px) {
    .topbar {
      flex-direction: column;
      align-items: stretch;
      padding: 0.25rem 0.25rem;
    }
    .main-content {
      padding: 6px;
      margin-top: 60px;
    }
    .sidebar {
      width: 180px;
      padding: 0.5rem;
    }
  }

  .topbar {
    position: fixed;
    top: 10px;
    left: 260px;
    right: 10px;
    height: 56px;
    background-color: #FFFFFF; 
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    z-index: 1030;
    border-radius: 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .topbar *,
  .topbar i,
  .topbar .navbar-brand,
  .topbar .dropdown-toggle {
    color: #2F4858 !important;
  }

  .topbar .form-control:focus {
    box-shadow: none;
    border-color: #004F80;
  }

  .sidebar {
    position: fixed;
    left: 0;
    top: 10px;
    width: 250px;
    height: calc(100% - 16px);
    background-color: #FFFFFF;
    color: #2F4858;
    padding: 1rem;
    overflow-y: auto;
    border-right: 1px solid #D0D6DA;
    border-radius: 26px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    transition: transform 0.3s ease;
  }

  .sidebar .nav-link {
    color: #2F4858;
    border-radius: 8px;
    padding: 8px 12px;
  }

  .sidebar .nav-link:hover,
  .sidebar .nav-link.active {
    background-color: #E6F2F8;
    color: #004F80;
  }

  .btn-primary.rounded-circle {
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
    background-color: #004F80;
    border-color: #004F80;
    padding: 0;
  }

  .btn-primary.rounded-circle:hover {
    background-color: #003D66;
    border-color: #003D66;
  }

  .main-content {
    margin-left: 260px;
    margin-right: 10px;
    margin-top: 70px;
    height: calc(100% - 56px);
    overflow-y: auto;
    background-color: #FFFFFF;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }

  .dropdown-menu {
    background-color: #FFFFFF;
    border: 1px solid #D0D6DA;
  }

  .dropdown-menu a {
    color: #2F4858;
  }

  .dropdown-menu a:hover {
    background-color: #E6F2F8;
    color: #004F80;
  }

  .modal-backdrop {
      z-index: 1050 !important;
  }

  .modal {
      z-index: 1060 !important;
  }
  .modal.show {
      display: block;
  }
  </style>
</head>
<body>

<?php if (isLoggedIn()): ?>
<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center px-4 py-2 shadow rounded-4 border-0 bg-white flex-wrap">
  <div class="d-flex align-items-center flex-shrink-0">
    <img src="/TrackWeb/assets/images/logo.png" alt="Logo" class="img-fluid me-2" style="max-height: 40px;">
  </div>
  <button class="btn btn-light d-lg-none me-2" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
  </button>
  <form class="d-flex align-items-center me-4 flex-grow-1" action="<?php echo BASE_URL; ?>documents/results.php" method="get" style="min-width: 200px;">
    <input class="form-control rounded-pill me-2" type="search" name="search" placeholder="Search documents..." aria-label="Search" style="width: 100%; min-width: 120px;">
    <button class="btn btn-light rounded-circle" type="submit" title="Search"><i class="fas fa-search" style="color: #2AB7CA;"></i></button>
  </form>
  <a href="<?php echo BASE_URL; ?>documents/search.php" class="btn btn-light rounded-circle d-none d-md-inline" title="Advanced Search"><i class="fas fa-sliders-h" style="color: #2AB7CA;"></i></a>
  <!-- User Menu -->
  <div class="dropdown ms-2">
    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <span class="me-2">
        <i class="fas fa-user-circle fs-5 align-items-center" style="color: #004F80;"></i>
        <?php echo $username_display; ?>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
      <li class="dropdown-item text-center">
    <span class="d-inline-flex justify-content-center align-items-center rounded-circle bg-primary text-white" 
          style="width: 40px; height: 40px; font-weight: bold;">
        <?php echo $user_initial; ?>
    </span>
    <div class="mt-2 fw-semibold"><?php echo $username_display; ?></div>
</li>
<li><hr class="dropdown-divider"></li>
<li>
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout.php">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
    </a>
</li>
    </ul>
  </div>
</div>

<!-- Sidebar -->
<div class="sidebar shadow rounded-4 border-0" id="sidebar">
  <ul class="nav nav-pills flex-column mb-auto">
    <!-- Quick Action Button -->
    <div class="mb-3 text-center">
      <div class="dropdown">
        <button class="btn btn-primary rounded-circle" type="button" id="plusDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="New">
          <i class="fas fa-plus"></i>
        </button>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="plusDropdown">
          <li>
            <a class="dropdown-item" href="<?php echo BASE_URL; ?>documents/upload.php">
              <i class="fas fa-upload me-2" style="color: #004F80;"></i> Upload Document
            </a>
            <a class="dropdown-item" href="<?php echo BASE_URL; ?>documents/browse.php">
              <i class="fas fa-folder-open me-2" style="color: #004F80;"></i> Create Folder
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Common Links -->
    <li class="nav-item">
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>dashboard.php">
        <i class="fas fa-chart-line me-2" style="color: #004F80;"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'browse.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>documents/browse.php">
        <i class="fas fa-folder-tree me-2" style="color: #004F80;"></i> Browse
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shared.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>documents/shared.php">
        <i class="fas fa-users me-2" style="color: #004F80;"></i> Shared with Me
      </a>
    </li>
    <!-- Admin-Only Links -->
    <?php if (isAdmin()): ?>
      <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>categories/manage.php">
          <i class="fas fa-layer-group me-2" style="color: #004F80;"></i> Categories
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_user.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>users/manage_user.php">
          <i class="fas fa-user-gear me-2" style="color: #004F80;"></i> Users
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'audit.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>logs/audit.php">
          <i class="fas fa-clipboard-check me-2" style="color: #004F80;"></i> Activity Logs
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'system.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>settings/system.php">
          <i class="fas fa-sliders-h me-2" style="color: #004F80;"></i> System Settings
        </a>
      </li>
    <?php endif; ?>
    <!-- Other Links -->
    <li class="nav-item">
      <a class="nav-link" href="<?php echo BASE_URL; ?>storage.php">
        <i class="fas fa-database me-2" style="color: #004f80;"></i> Storage Usage
      </a>
    </li>
      <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'trash.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>documents/trash.php">
          <i class="fas fa-trash-alt me-2" style="color: #004F80;"></i> Recycle Bin
        </a>
      </li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
<?php endif; ?>

<!-- SweetAlert Notifications -->
<?php if (isset($_GET['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: <?php echo json_encode($_GET['success']); ?>,
      confirmButtonColor: '#3085d6'
    });
  </script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: <?php echo json_encode($_GET['error']); ?>,
      confirmButtonColor: '#d33'
    });
  </script>
<?php endif; ?>

<script>
  // Sidebar toggle for mobile
  document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    var sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
      });
      // Hide sidebar when clicking outside (mobile)
      document.addEventListener('click', function (e) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('show')) {
          sidebar.classList.remove('show');
        }
      });
    }
  });
  // Remove success/error from URL after showing SweetAlert
  if (window.location.search.includes("success") || window.location.search.includes("error")) {
    const url = new URL(window.location);
    url.search = "";
    window.history.replaceState({}, document.title, url);
  }
</script>