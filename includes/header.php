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
  <title>Tvet Record Archival and Control Kiosk</title>
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/header.css">
  
  <!-- External Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
  <!-- JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/LOGO.png">
</head>

<body>
<?php if (isLoggedIn()): ?>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column" id="sidebar">
  <!-- Collapse Toggle -->
  <div class="sidebar-header mb-3">
    <div class="logo-container">
      <span class="logo-text fw-bold text-white fs-5">TRACK</span>
      <button class="btn btn-light rounded-circle collapse-btn" id="collapseToggleBtn" title="Collapse Sidebar">
        <i class="fas fa-chevron-left"></i>
      </button>
    </div>
  </div>

  <ul class="nav nav-pills flex-column mb-auto border-top pt-3">
    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>dashboard.php">
        <i class="fas fa-chart-line me-2"></i><span>Dashboard</span>
      </a>
    </li>

    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'browse.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>documents/browse.php">
        <i class="fas fa-folder-tree me-2"></i><span>Browse</span>
      </a>
    </li>

    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shared.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>documents/shared.php">
        <i class="fas fa-users me-2"></i><span>Shared with Me</span>
      </a>
    </li>

    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'storage.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>storage.php">
        <i class="fas fa-database me-2"></i><span>Storage Usage</span>
      </a>
    </li>

    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'archive_document.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>documents/archive_document.php">
        <i class="fas fa-box-archive me-2"></i><span>Archived</span>
      </a>
    </li>

    <li>
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'trash.php' ? 'active' : ''; ?>" 
         href="<?php echo BASE_URL; ?>documents/trash.php">
        <i class="fas fa-trash-alt me-2"></i><span>Recycle Bin</span>
      </a>
    </li>
  </ul>

  <!-- Logout -->
  <div class="mt-auto border-top pt-3">
    <a class="nav-link text-danger fw-semibold" href="<?php echo BASE_URL; ?>auth/logout.php">
      <i class="fas fa-sign-out-alt me-2"></i><span>Logout</span>
    </a>
  </div>
</div>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center px-4 py-2" id="topbar">
  <!-- Sidebar Toggle for Mobile -->
  <button class="btn btn-light rounded-circle d-lg-none" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <div class="d-flex align-items-center flex-shrink-0">
    <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="TrackWeb Logo" class="img-fluid me-2" style="max-height: 40px;">
  </div>

  <form class="d-flex align-items-center me-4 flex-grow-1 search-form"
        action="<?php echo BASE_URL; ?>documents/results.php" method="get">
    <input class="form-control me-2 rounded-pill" type="search" name="search" placeholder="Search documents..." aria-label="Search">
    <button class="btn btn-light rounded-circle d-none d-md-inline me-2" type="submit" title="Search">
      <i class="fas fa-search"></i>
    </button>
  </form>

  <?php if (isAdmin()): ?>
  <a href="<?php echo BASE_URL; ?>settings/system.php" 
     class="btn btn-light rounded-circle d-none d-md-inline me-2" title="Advanced Search">
    <i class="fas fa-cog"></i>
  </a>
  <?php endif; ?>

  <a href="<?php echo BASE_URL; ?>documents/search.php" 
     class="btn btn-light rounded-circle d-none d-md-inline me-2" title="Advanced Search">
    <i class="fas fa-filter"></i>
  </a>

  <div>
    <button class="btn btn-light d-flex align-items-center px-2 py-1 rounded-pill"
            type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
      <div class="d-flex align-items-center">
        <div style="width: 34px; height: 34px; border-radius: 50%;
                    background: linear-gradient(135deg, #004F80, #0073b6);
                    display: flex; align-items: center; justify-content: center;
                    color: white !important; font-weight: 600; font-size: 0.9rem;">
          <?php echo $user_initial; ?>
        </div>
      </div>
    </button>
  </div>
</div>

<!-- Main Content -->
<main class="main-content" id="mainContent">
<?php endif; ?>

<!-- Alerts -->
<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({ title:'Success', text:<?php echo json_encode($_GET['success']); ?>, icon:'success', confirmButtonColor:'#004F80' });
</script>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
<script>
Swal.fire({ title:'Error', text:<?php echo json_encode($_GET['error']); ?>, icon:'error', confirmButtonColor:'#2F4858' });
</script>
<?php endif; ?>

<script src="<?php echo BASE_URL; ?>assets/js/header.js"></script>