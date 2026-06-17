<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_login'])) {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password)) {
        header("Location: ../pages/login.php?error=empty_fields");
        exit();
    }

    try {
        $user_sql = "SELECT user_id, password, role, status FROM user WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($user_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows === 1) {
            $user = $user_result->fetch_assoc();

            // 3. Verify the submitted password against the stored BCRYPT hash
            if (password_verify($password, $user['password'])) {
                
                // 4. Verification Check Gate (Check if account is verified)
                if ($user['status'] === 'pending') {
                    // Cache email in session so the verification page knows who is trying to activate
                    $_SESSION['temp_verify_email'] = $email;
                    
                    header("Location: ../pages/login.php?error=unverified");
                    exit();
                }
                
                // Extra security condition checking if an account is suspended or inactive
                if ($user['status'] !== 'active') {
                    header("Location: ../pages/login.php?error=account_disabled");
                    exit();
                }

                // 5. Successful validation! Establish authorization tokens in session scope
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role']; // Student, Lecturer, or Company
                $_SESSION['email'] = $email;

                // 6. Fetch the user's display name from their role-specific sub-profile table
                $display_name = "User";
                $redirect_target = '/MYIntern/index.php'; // Default fallback
                
                if ($user['role'] === 'Student') {
                    $profile_stmt = $conn->prepare("SELECT full_name FROM student WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();
                    
                    $display_name = $profile['full_name'] ?? 'Student';
                    $redirect_target = '/MYIntern/index.php'; // Students route to main job list
                } 
                elseif ($user['role'] === 'Lecturer') {
                    $profile_stmt = $conn->prepare("SELECT full_name FROM lecturer WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();
                    
                    $display_name = $profile['full_name'] ?? 'Lecturer';
                    $redirect_target = '/MYIntern/pages/lecturer_dashboard.php';
                } 
                elseif ($user['role'] === 'Company') {
                    $profile_stmt = $conn->prepare("SELECT company_name FROM company WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();
                    
                    $display_name = $profile['company_name'] ?? 'Company';
                    $redirect_target = '/MYIntern/pages/company_dashboard.php';
                }

                $_SESSION['display_name'] = $display_name;

                // Native Synchronous redirect to the designated dashboard matching their role
                header("Location: " . $redirect_target);
                exit();

            } else {
                // Password execution mismatch fallback
                header("Location: ../pages/login.php?error=invalid_credentials");
                exit();
            }
        } else {
            // Email address not found fallback
            header("Location: ../pages/login.php?error=invalid_credentials");
            exit();
        }

    } catch (Exception $e) {
        error_log("Login System Fatal Fault: " . $e->getMessage());
        header("Location: ../pages/login.php?error=system_fault");
        exit();
    }
} else {
    // If someone tries to access this file directly via URL parameters without posting form parameters
    header("Location: ../pages/login.php");
    exit();
}