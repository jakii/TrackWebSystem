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
<div class="sidebar d-flex flex-column slide-in-left" id="sidebar">
  <!--  Collapse Toggle -->
  <div class="mb-3 text-center">
    <button class="btn btn-primary rounded-circle" id="collapseToggleBtn" title="Collapse Sidebar">
      <i class="fas fa-chevron-left"></i>
    </button>
  </div>

  <!-- Nav Links -->
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

    <?php if (isAdmin()): ?>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>categories/manage.php"><i class="fas fa-layer-group me-2"></i><span>Categories</span></a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_user.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>users/manage_user.php"><i class="fas fa-user-gear me-2"></i><span>Users</span></a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'audit.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>logs/audit.php"><i class="fas fa-clipboard-check me-2"></i><span>Activity Logs</span></a></li>
    <?php endif; ?>

    <li><a class="nav-link" href="<?php echo BASE_URL; ?>storage.php"><i class="fas fa-database me-2"></i><span>Storage Usage</span></a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'trash.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>documents/trash.php"><i class="fas fa-trash-alt me-2"></i><span>Recycle Bin</span></a></li>
  </ul>

  <!--  Logout -->
  <div class="mt-auto border-top pt-3">
    <a class="nav-link text-danger fw-semibold" href="<?php echo BASE_URL; ?>auth/logout.php">
      <i class="fas fa-sign-out-alt me-2"></i><span>Logout</span>
    </a>
  </div>
</div>

<!-- Topbar -->
<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center px-4 py-2" id="topbar">
  <button class="btn btn-light d-lg-none me-2" id="sidebarToggle"><i class="fas fa-bars"></i></button>
  <div class="d-flex align-items-center flex-shrink-0">
    <img src="/TrackWeb/assets/images/logo.png" alt="TrackWeb Logo" class="img-fluid me-2" style="max-height: 40px;">
  </div>

  <form class="d-flex align-items-center me-4 flex-grow-1 search-form" 
        action="<?php echo BASE_URL; ?>documents/results.php" method="get">
    <input class="form-control me-2 rounded-pill" type="search" name="search" placeholder="Search documents..." aria-label="Search">
    <button class="btn btn-light rounded-circle d-none d-md-inline me-2" type="submit" title="Search">
      <i class="fas fa-search"></i>
    </button>
  </form>

  <a href="<?php echo BASE_URL; ?>documents/search.php" 
     class="btn btn-light rounded-circle d-none d-md-inline me-2" title="Advanced Search">
    <i class="fas fa-filter"></i>
  </a>
  
  <div class="dropdown">
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

    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu">
      <li>
        <a class="dropdown-item" href="<?php echo BASE_URL; ?>profile.php">
          <i class="fas fa-user me-2 text-muted"></i>My Profile
        </a>
      </li>
      <?php if (isAdmin()): ?>
        <li>
          <a class="dropdown-item" href="<?php echo BASE_URL; ?>settings/system.php">
            <i class="fas fa-cog me-2 text-muted"></i>Settings
          </a>
        </li>
      <?php endif; ?>
      <li><hr class="dropdown-divider"></li>
      <li>
        <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>auth/logout.php">
          <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
      </li>
    </ul>
  </div>
</div>


<!--  Main Content -->
<main class="main-content" id="mainContent">
<?php endif; ?>

<!--  Alerts -->
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const topbar = document.getElementById('topbar');
  const main = document.getElementById('mainContent');
  const sidebarToggle = document.getElementById('sidebarToggle'); // mobile bars button
  const collapseToggleBtn = document.getElementById('collapseToggleBtn'); // inside sidebar
  const collapseIcon = collapseToggleBtn?.querySelector('i');

  // ---  Restore Sidebar State on Load ---
  const savedState = localStorage.getItem('sidebar-collapsed');
  if (savedState === 'true') {
    sidebar.classList.add('sidebar-collapsed');
    applyCollapsedLayout(true);
  }

  // Mobile Sidebar Toggle
  sidebarToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });

  // Hide sidebar on outside click (mobile only)
  document.addEventListener('click', e => {
    if (window.innerWidth < 992 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
      sidebar.classList.remove('show');
    }
  });

  // Desktop Collapse Toggle
  collapseToggleBtn?.addEventListener('click', function () {
    const collapsed = sidebar.classList.toggle('sidebar-collapsed');
    applyCollapsedLayout(collapsed);
    localStorage.setItem('sidebar-collapsed', collapsed);
  });

  // Auto-reset sidebar visibility on resize
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) sidebar.classList.remove('show');
  });

  // Clean URL parameters
  if (window.location.search.includes('success') || window.location.search.includes('error')) {
    const url = new URL(window.location);
    url.search = '';
    window.history.replaceState({}, document.title, url);
  }

  // === Function: Apply Layout Changes ===
  function applyCollapsedLayout(isCollapsed) {

    sidebar.style.transition = 'width 0.3s ease';
    main.style.transition = 'margin-left 0.3s ease, width 0.3s ease';
    topbar.style.transition = 'left 0.3s ease';

    if (isCollapsed) {
      main.style.marginLeft = '90px';
      main.style.width = 'calc(100% - 100px)';
      topbar.style.left = '90px';
      collapseIcon?.classList.replace('fa-chevron-left', 'fa-chevron-right');
      collapseToggleBtn?.setAttribute('title', 'Expand Sidebar');
    } else {
      main.style.marginLeft = '250px';
      main.style.width = 'calc(100% - 260px)';
      topbar.style.left = '250px';
      collapseIcon?.classList.replace('fa-chevron-right', 'fa-chevron-left');
      collapseToggleBtn?.setAttribute('title', 'Collapse Sidebar');
    }
  }
});

    document.addEventListener('hidden.bs.modal', function () {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
    });
</script>