<?php
require_once '../api/api_login.php';
require_once '../includes/header.php';
?>
<link rel="icon" href="../assets/images/LOGO.png" type="image/x-icon">

<!-- External CSS -->
<link rel="stylesheet" href="../assets/css/login.css">

<!-- Background Decorations -->
<div class="bg-decoration circle-1"></div>
<div class="bg-decoration circle-2"></div>

<!-- Back to Home Link -->
<a href="../index.php" class="back-home">
    <i class="fas fa-arrow-left"></i>
    <span>Back to Home</span>
</a>

<!-- Login Container -->
<div class="login-container">
    <div class="login-card">
        <!-- Logo/Icon -->
        <div class="logo-container">
            <div class="logo-icon">
                <i class="fas fa-lock"></i>
            </div>
        </div>

        <h1 class="login-title">Welcome Back</h1>
        <p class="login-subtitle">Sign in to continue to your account</p>

        <?php if (!empty($error)): ?>
            <div class="alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off" novalidate id="loginForm">
            <div class="mb-4">
                <label for="username" class="form-label-custom">Username or Email</label>
                <input 
                    type="text" 
                    class="form-control-custom" 
                    id="username" 
                    name="username" 
                    value="<?php echo htmlspecialchars($username ?? ''); ?>"
                    placeholder="Enter your username or email"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label-custom">Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        class="form-control-custom" 
                        id="password" 
                        name="password"
                        placeholder="Enter your password"
                        required
                        style="padding-right: 50px;"
                    >
                    <span class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <div class="register-link-container">
            <p class="register-text">
                Don't have an account? 
                <a href="register.php" class="register-link">Create one now</a>
            </p>
        </div>
    </div>
</div>

<!-- External JS -->
<script src="../assets/js/login.js"></script>
