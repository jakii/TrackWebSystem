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
<style>
  body {
    background-color: #F4F6F8;
    color: #2F4858;
  }
  .brand-name {
    font-size: 8rem;
    font-weight: 800;
    color: #004F80;
    margin-top: -50px;
  }
  .subtitle {
    font-size: 1.5rem;
    color: #2F4858;
    font-weight: 500;
    margin-top: -10px;
  }
  .btn-login {
    background-color: #004F80;
    color: #fff;
    font-weight: 500;
    border: none;
    box-shadow: 0 2px 8px #004f801a;
    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
  }
  .btn-login:hover {
    background-color: #004F80;
    color: #fff;
    box-shadow: 0 6px 24px #004f802e;
    transform: translateY(-2px) scale(1.04);
  }
  .btn-register {
    background-color: #FFD166;
    color: #2F4858;
    font-weight: 700;
    border: none;
    box-shadow: 0 2px 8px #ffd1661a;
    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
  }
  .btn-register:hover {
    background: linear-gradient(90deg, #E6B958 0%, #FFD166 100%);
    color: #004F80;
    box-shadow: 0 6px 24px rgba(255,209,102,0.18);
    transform: translateY(-2px) scale(1.04);
  }
  .hr {
    width: 600px;
    border: 2px solid #D0D6DA;
    margin-top: -50px;
    margin-left: auto;
    margin-right: auto;
  }
  .feature-card {
    background: rgba(255,255,255,0.7);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,79,128,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    overflow: hidden;
  }
  .feature-card:hover {
    transform: translateY(-6px) scale(1.04);
    box-shadow: 0 8px 32px rgba(0,79,128,0.16);
  }
  .feature-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    box-shadow: 0 2px 8px rgba(0,79,128,0.10);
    margin: 0 auto 18px auto;
    font-size: 2rem;
    color: #004F80;
  }
  .feature-title {
    font-weight: 700;
    color: #004F80;
    margin-bottom: 8px;
  }
  .feature-desc {
    color: #2F4858;
    font-size: 1rem;
    opacity: 0.85;
  }
  @media (max-width: 768px) {
    .feature-card {
      padding: 18px 8px;
    }
    .feature-icon {
      width: 40px;
      height: 40px;
      font-size: 1.3rem;
    }
  }
  .navbar {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }
  .navbar-brand {
    font-size: 1.5rem;
    color: #004F80;
  }
  .navbar-brand:hover {
    color: #003D66;
  }
  #preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
  .progress-bar-container {
    width: 320px;
    height: 18px;
    background: #e0eafc;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,79,128,0.08);
    margin-top: 18px;
    overflow: hidden;
  }
  .progress-bar {
    height: 100%;
    width: 0%;
    background-color: #004F80;
    border-radius: 12px;
    transition: width 0.6s cubic-bezier(.77,0,.18,1);
  }
  /* Fade-in Animations */
  .fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease forwards;
  }
  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .delay-1 { animation-delay: 0.2s; }
  .delay-2 { animation-delay: 0.4s; }
  .delay-3 { animation-delay: 0.6s; }
  .delay-4 { animation-delay: 0.8s; }
  .delay-5 { animation-delay: 1s; }
</style>
</head>
<body>
  <!-- Preloader -->
  <div id="preloader">
    <img src="assets/images/logo.png" alt="Logo" style="height: 80px; width: 80px;" class="mb-3">
    <div class="progress-bar-container">
      <div class="progress-bar" id="progress-bar"></div>
    </div>
    <p class="mt-3 fw-semibold text-primary">Loading, please wait...</p>
  </div>

  <!-- Main Content -->
  <div id="main-content" style="display: none;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-primary bg-white fixed-top shadow-sm fade-in delay-1">
      <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
          <img src="assets/images/logo.png" alt="Logo" style="height: 40px; width: 40px;" class="me-2">
          <span>TRACK</span>
        </a>
        <a href="auth/login.php">
          <button class="btn btn-login px-4 rounded-pill shadow-sm">
            <i class="fas fa-sign-in-alt me-2"></i>Get Started
          </button>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
      <div class="text-center fade-in delay-2">
        <div class="d-flex align-items-center justify-content-center mb-2 fade-in delay-3">
          <img src="assets/images/logo.png" alt="Logo" style="height: 110px; width: 110px; margin-right: 2px; margin-bottom: 50px;">
          <div class="brand-name" style="font-size: 5rem; font-weight: bold; color: #004F80;">TRACK</div>
        </div>
        <hr class="hr fade-in delay-3">
        <div class="subtitle fade-in delay-4">TVET Record And Archival Control Kiosk</div>

        <div class="mt-4 fade-in delay-4">
          <a href="auth/login.php" class="btn btn-login px-4 me-2 rounded-pill shadow-sm">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </a>
          <a href="auth/register.php" class="btn btn-register px-4 rounded-pill shadow-sm">
            <i class="fas fa-user-plus me-2"></i>Register
          </a>
        </div>

        <!-- Feature Cards -->
        <div class="container mt-4 fade-in delay-5">
          <div class="row g-4">
            <div class="col-md-4">
              <div class="feature-card p-4 text-center h-100">
                <div class="feature-icon"><i class="fas fa-folder"></i></div>
                <div class="feature-title">Organized Folders</div>
                <div class="feature-desc">Easily manage your TVET documents by folder and category.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-card p-4 text-center h-100">
                <div class="feature-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="feature-title">Quick Upload</div>
                <div class="feature-desc">Upload and archive important records in just a few clicks.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-card p-4 text-center h-100">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="feature-title">Secure Access</div>
                <div class="feature-desc">Role-based system ensures data privacy and user control.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Animate progress bar
    let progress = 0;
    const progressBar = document.getElementById('progress-bar');
    const interval = setInterval(function() {
      if (progress < 100) {
        progress += Math.random() * 20;
        if (progress > 100) progress = 100;
        progressBar.style.width = progress + '%';
      }
    }, 200);
    window.addEventListener('load', function () {
      setTimeout(function () {
        progressBar.style.width = '100%';
        setTimeout(function() {
          document.getElementById('preloader').style.display = 'none';
          document.getElementById('main-content').style.display = 'block';
          clearInterval(interval);
        }, 400);
      }, 1000);
    });
  </script>
</body>
</html>

