<?php
session_start();
include('../includes/db_connection.php'); 

$error_message = "";

if (isset($_POST['submit_login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $sql = "SELECT id, full_name FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_name'] = $user['full_name']; 
        header("Location: student/student_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
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

                <form action="student/student_application.php" method="POST" id="loginForm">

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

                    <div class="forgot-block">
                        <a href="forgot_password.php" style="color: #111E4B; text-decoration: none; font-weight: 500;">Forgot Password?</a>
                        <p style="color: #718096;">Don't have an account? <a href="sign_up.php" style="color: #E2C279; text-decoration: none; font-weight: 600;">Register here</a></p>
                    </div>

                    <div class="form-action-row">
                        <button id="login-btn" type="submit" name="submit_login" class="signup-submit-btn">Login</button>
                    </div>

                    <?php if (!empty($error_message)): ?>
                        <div style="color: #9B1C1C; background-color: #FDE8E8; padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 6px; font-family: 'Google Sans', sans-serif; font-size: 0.9rem;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </main>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const roleButtons = document.querySelectorAll(".role-nav-btn");
                const roleInput = document.getElementById("userRoleInput");

                roleButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        roleButtons.forEach(btn => btn.classList.remove("active"));
                        this.classList.add("active");

                        const selectedRole = this.getAttribute("data-target-role");
                        roleInput.value = selectedRole;
                    });
                });

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
