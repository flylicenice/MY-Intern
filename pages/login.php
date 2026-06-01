<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="signup-body">

    <div class="signup-split-container">

        <aside class="role-selection-sidebar" id="login-sidebar">
            <h1 class="sidebar-logo">MYIntern</h1>

            <div class="role-toggle-group">
                <p class="toggle-heading">Are you a...</p>

                <button type="button" class="role-nav-btn active" data-target-role="Student">
                    Student
                </button>

                <button type="button" class="role-nav-btn" data-target-role="Lecturer">
                    Lecturer
                </button>

                <p class="toggle-heading secondary-heading">Or manage your company now!</p>

                <button type="button" class="role-nav-btn" data-target-role="Company">
                    Employer
                </button>
            </div>
        </aside>

        <main class="signup-form-canvas">
    <div class="form-scroll-wrapper">
        <h2 class="form-title">Welcome Back!</h2>
        
        <form action="../actions/login_process.php" method="POST" id="loginForm">
            
            <input type="hidden" name="role" id="userRoleInput" value="Student">

            <div class="form-grid">
                
                <div class="input-block full-width">
                    <input type="email" name="email" placeholder="Email Address" required autocomplete="username">
                </div>

                <div class="input-block full-width" style="position: relative;">
                    <input type="password" name="password" id="loginPasswordInput" placeholder="Password" required autocomplete="current-password">
                    <i class='bx bx-hide' id="togglePasswordVisibility"></i>
                </div>

            </div>

            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0 1.5rem 0; font-size: 0.9rem;">
                <a href="forgot_password.php" style="color: #111E4B; text-decoration: none; font-weight: 500;">Forgot Password?</a>
                <span style="color: #718096;">Don't have an account? <a href="sign_up.php" style="color: #E2C279; text-decoration: none; font-weight: 600;">Register here</a></span>
            </div>

            <div class="form-action-row">
                <button type="submit" name="submit_login" class="signup-submit-btn" style="background-color: #111E4B; color: #FFFFFF; border-color: #111E4B; width: 100%;">Login</button>
            </div>

        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. Keep Left Sidebar Role Selector Synchronized ---
        const roleButtons = document.querySelectorAll(".role-nav-btn");
        const roleInput = document.getElementById("userRoleInput");

        roleButtons.forEach(button => {
            button.addEventListener("click", function() {
                roleButtons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");
                
                // Keep backend informed whether a Student, Lecturer, or Employer is attempting login
                const selectedRole = this.getAttribute("data-target-role");
                roleInput.value = selectedRole;
            });
        });

        // --- 2. Interactive Password Eye Toggle Logic ---
        const passwordField = document.getElementById("loginPasswordInput");
        const visibilityToggle = document.getElementById("togglePasswordVisibility");

        if (visibilityToggle && passwordField) {
            visibilityToggle.addEventListener("click", function() {
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    this.classList.replace("bx-hide", "bx-show");
                } else {
                    passwordField.type = "password";
                    this.classList.replace("bx-show", "bx-hide");
                }
            });
        }
    });
</script>

</html>