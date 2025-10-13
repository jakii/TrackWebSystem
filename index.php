<?php
require_once 'config/config.php';
require_once 'includes/auth_check.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo APP_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="assets/images/LOGO.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/landing.css">
<style>

</style>
</head>
<body>
  <!-- Background Decorations -->
  <div class="bg-decoration circle-1"></div>
  <div class="bg-decoration circle-2"></div>
  <div class="bg-decoration circle-3"></div>

  <!-- Preloader -->
  <div id="preloader">
    <img src="assets/images/logo.png" alt="Logo" class="preloader-logo" style="height: 100px; width: 100px;">
    <div class="progress-bar-container">
      <div class="progress-bar" id="progress-bar"></div>
    </div>
    <p class="mt-4 fw-semibold" style="color: #004F80; font-size: 1.1rem;">Loading your experience...</p>
  </div>

  <!-- Main Content -->
  <div id="main-content" style="display: none;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top fade-in delay-1">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="assets/images/logo.png" alt="Logo" style="height: 45px; width: 45px;" class="me-2">
          <span>TRACK</span>
        </a>
        <a href="auth/login.php">
          <button class="btn btn-primary-custom">
            <i class="fas fa-sign-in-alt me-2"></i>Get Started
          </button>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-container container text-center">
        <!-- Logo and Brand -->
        
        <h1 class="brand-name fade-in delay-3">TRACK</h1>
        
        <div class="divider fade-in delay-3"></div>
        
        <p class="subtitle fade-in delay-4">TVET Record And Archival Control Kiosk</p>

        <!-- CTA Buttons -->
        <div class="mt-5 fade-in delay-4">
          <a href="auth/login.php" class="btn btn-primary-custom me-3 mb-3">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </a>
          <a href="auth/register.php" class="btn btn-secondary-custom mb-3">
            <i class="fas fa-user-plus me-2"></i>Register Now
          </a>
        </div>

        <!-- Feature Cards -->
        <div class="container mt-5 pt-4 fade-in delay-5">
          <div class="row g-4">
            <div class="col-md-4">
              <div class="feature-card h-100">
                <div class="feature-icon">
                  <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="feature-title">Organized Folders</h3>
                <p class="feature-desc">Effortlessly manage and categorize your TVET documents with our intuitive folder system.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-card h-100">
                <div class="feature-icon">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h3 class="feature-title">Quick Upload</h3>
                <p class="feature-desc">Upload and archive important records in seconds with our streamlined process.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-card h-100">
                <div class="feature-icon">
                  <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Secure Access</h3>
                <p class="feature-desc">Role-based authentication ensures maximum data privacy and controlled access.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Preloader Animation
    let progress = 0;
    const progressBar = document.getElementById('progress-bar');
    const interval = setInterval(function() {
      if (progress < 100) {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;
        progressBar.style.width = progress + '%';
      }
    }, 150);

    window.addEventListener('load', function () {
      setTimeout(function () {
        progressBar.style.width = '100%';
        setTimeout(function() {
          document.getElementById('preloader').style.opacity = '0';
          document.getElementById('preloader').style.transition = 'opacity 0.5s';
          setTimeout(function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
            clearInterval(interval);
          }, 500);
        }, 300);
      }, 800);
    });
  </script>
</body>
</html>