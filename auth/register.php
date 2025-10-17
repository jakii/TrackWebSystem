<?php 
require_once '../api/api_register.php';
require_once '../includes/header.php';
?>
<link rel="icon" href="../assets/images/LOGO.png" type="image/x-icon">

<!-- External CSS -->
<link rel="stylesheet" href="../assets/css/register.css">

<!-- Back to Home Button -->
<a href="../index.php" class="back-home">
    <i class="fas fa-arrow-left"></i> Back to Home
</a>

<!-- Background Circles -->
<div class="bg-circle one"></div>
<div class="bg-circle two"></div>

<!-- Register Container -->
<div class="register-container">
    <div class="register-card">
        <!-- Header -->
        <div class="register-header">
            <div class="logo-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create an Account</h1>
            <p>Fill out the form below to get started</p>
        </div>

        <!-- Alerts -->
        <?php if (!empty($error)): ?>
            <div class="alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form method="POST" autocomplete="off" novalidate id="registerForm">
            <div class="form-group">
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    value="<?php echo htmlspecialchars($full_name ?? ''); ?>" 
                    placeholder="Enter your full name"
                    required
                >
            </div>

            <div class="form-group">
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                    placeholder="Choose a username"
                    required
                >
            </div>

            <div class="form-group">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                    placeholder="Enter your email address"
                    required
                >
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        minlength="6"
                        placeholder="Create a password (min. 8 characters)"
                        required
                    >
                    <span class="toggle-password" id="togglePassword1">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Re-enter your password"
                        required
                    >
                    <span class="toggle-password" id="togglePassword2">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-user-plus me-2"></i>Register
            </button>
        </form>

        <div class="register-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<script>
// Password Toggle for Password Field
document.getElementById('togglePassword1').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Password Toggle for Confirm Password Field
document.getElementById('togglePassword2').addEventListener('click', function() {
    const passwordInput = document.getElementById('confirm_password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form Submit Loading State
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.classList.add('loading');
    submitBtn.innerHTML = '<span style="opacity: 0;">Processing...</span>';
});

// Input Focus Animation
const inputs = document.querySelectorAll('.form-group input');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
        this.parentElement.style.transition = 'transform 0.3s ease';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
    });
});
</script>