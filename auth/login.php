<?php
include '../api/api_login.php';
include '../includes/header.php';
?>
<link rel="icon" href="assets/images/LOGO.png" type="image/x-icon">
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm rounded-4 border-0 animate-fade-slide">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="feature-icon"><i class="fas fa-lock fa-2x mb-2" style="color: #004F80;"></i></div>
                    <h4 class="fw-semibold">Sign in to your account</h4>
                    <p class="text-muted small">Enter your credentials below to continue</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-medium">Username or Email</label>
                        <input type="text" class="form-control form-control-sm rounded-3" id="username" name="username" 
                               value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <input type="password" class="form-control form-control-sm rounded-3" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn w-100 rounded-pill py-2 fw-semibold" style="background-color: #004F80; color: white;">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Don't have an account?
                        <a href="register.php" class="text-decoration-none fw-medium">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
