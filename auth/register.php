<?php 
include '../api/api_register.php';
include '../includes/header.php';
?>
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="col-md-5 col-lg-5">
        <div class="card shadow-sm rounded-4 border-0 animate-fade-slide">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus fa-2x mb-2" style="color: #004F80;"></i>
                    <h4 class="fw-semibold">Create an Account</h4>
                    <p class="text-muted small">Fill out the form below to register</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="full_name" class="form-label fw-medium">Full Name</label>
                        <input type="text" class="form-control form-control-sm rounded-3" id="full_name" name="full_name" 
                            value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-medium">Username</label>
                        <input type="text" class="form-control form-control-sm rounded-3" id="username" name="username" 
                            value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" class="form-control form-control-sm rounded-3" id="email" name="email" 
                            value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <input type="password" class="form-control form-control-sm rounded-3" id="password" name="password" required>
                        <div class="form-text">Password must be at least 6 characters long.</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label fw-medium">Confirm Password</label>
                        <input type="password" class="form-control form-control-sm rounded-3" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn w-100 rounded-pill py-2 fw-semibold" style="background-color: #004F80; color: white;">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Already have an account?
                        <a href="login.php" class="text-decoration-none fw-medium">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
