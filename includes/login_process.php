<?php

session_start();
require_once __DIR__ . '/../includes/db.php';

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

            if (password_verify($password, $user['password'])) {

                if ($user['status'] === 'pending') {
                    $_SESSION['temp_verify_email'] = $email;

                    header("Location: ../pages/login.php?error=unverified");
                    exit();
                }

                if ($user['status'] !== 'active') {
                    header("Location: ../pages/login.php?error=account_disabled");
                    exit();
                }

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role']; // Student, Lecturer, Company or Admin

                $display_name = "User";
                $redirect_target = '/MYIntern/index.php';

                if ($user['role'] === 'Student') {
                    $profile_stmt = $conn->prepare("SELECT full_name FROM student WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();

                    $display_name = $profile['full_name'] ?? 'Student';
                    $redirect_target = '/MYIntern/index.php';
                } elseif ($user['role'] === 'Lecturer') {
                    $profile_stmt = $conn->prepare("SELECT full_name FROM lecturer WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();

                    $display_name = $profile['full_name'] ?? 'Lecturer';
                    $redirect_target = '/MYIntern/pages/lecturer/lecturer_dashboard.php?page=main';
                } elseif ($user['role'] === 'Company') {
                    $profile_stmt = $conn->prepare("SELECT company_name FROM company WHERE user_id = ? LIMIT 1");
                    $profile_stmt->bind_param("i", $user['user_id']);
                    $profile_stmt->execute();
                    $profile = $profile_stmt->get_result()->fetch_assoc();

                    $display_name = $profile['company_name'] ?? 'Company';
                    $redirect_target = '/MYIntern/pages/company/company_dashboard.php?page=main';
                } 

                $_SESSION['display_name'] = $display_name;

                header("Location: " . $redirect_target);
                exit();
            } else {
                header("Location: ../pages/login.php?error=invalid_credentials");
                exit();
            }
        } else {
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
