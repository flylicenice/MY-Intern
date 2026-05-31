<?php
// actions/sign_up_process.php

// 1. Establish core pipeline connections 
// Paths resolve relative to the file location inside an actions/ subfolder
require_once '../config/db.php'; 
require_once '../includes/session.php'; // Resolves from your file structure

// Ensure code triggers only on legitimate POST submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_register'])) {
    
    // ---------------------------------------------------------
    // 2. GATHER & SANITIZE SHARED PARENT VALUES (user table)
    // ---------------------------------------------------------
    $email    = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = trim($_POST['role']); // Student, Lecturer, or Company
    $status = 'pending';

    // Basic common validations
    if (!$email || empty($password) || empty($confirm_password) || empty($role)) {
        header("Location: ../pages/login.php?error=empty_fields");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: ../pages/login.php?error=password_mismatch");
        exit();
    }

    // Restrict inputs strictly to your system's valid ENUM values
    $allowed_roles = ['Student', 'Lecturer', 'Company'];
    if (!in_array($role, $allowed_roles)) {
        header("Location: ../pages/login.php?error=invalid_role");
        exit();
    }

    // ---------------------------------------------------------
    // 3. GATHER ROLE-SPECIFIC SUBTYPE VALUES
    // ---------------------------------------------------------
    if ($role === 'Student') {
        $matric_number     = trim($_POST['matric_no']);
        $full_name         = trim($_POST['fullname']);
        $identification_no = trim($_POST['ic_no']);
        $course            = trim($_POST['course']);
        $phone_number      = trim($_POST['phone_number']);
        $intern_status     = 'Inactive'; // Default standard start state

        /*if (empty($matric_number) || empty($full_name) || empty($identification_no) || empty($course) || empty($phone_number)) {
            header("Location: ../pages/login.php?error=empty_student_fields");
            exit();
        }*/
    } 
    elseif ($role === 'Lecturer') {
        $staff_id          = trim($_POST['staff_id']);
        $full_name         = trim($_POST['fullname']);
        $identification_no = trim($_POST['ic_no']);
        $phone_number      = trim($_POST['phone_number']);

        /*if (empty($staff_id) || empty($full_name) || empty($identification_no) || empty($phone_number)) {
            header("Location: ../pages/login.php?error=empty_lecturer_fields");
            exit();
        }*/
    } 
    elseif ($role === 'Company') {
        $registration_no   = trim($_POST['registration_no'] ?? ''); // Fallbacks based on form configurations
        $company_name      = trim($_POST['company_name']);
        $verification_status = 'pending'; // Base baseline review status

        /*if (empty($company_name)) {
            header("Location: ../pages/login.php?error=empty_company_fields");
            exit();
        }*/
    }

    // ---------------------------------------------------------
    // 4. DATABASE ACQUISITION PIPELINE (With Transactions)
    // ---------------------------------------------------------
    try {
        // Turn off auto-commit to begin a secure multi-table transaction block
        $conn->begin_transaction();

        // Check if email is already taken in the user table
        $check_sql = "SELECT user_id FROM user WHERE email = ? LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            header("Location: ../pages/login.php?error=email_taken");
            exit();
        }

        // Hash credentials securely using industry-grade bcrypt protection
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // STEP A: Insert core authentication credentials into the primary parent table
        $user_sql = "INSERT INTO user (email, password, role, status, time_created) VALUES (?, ?, ?, ?, NOW())";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("ssss", $email, $hashed_password, $role, $status);
        $user_stmt->execute();
        
        // Grab the auto-incremented primary key ID from step A
        $new_user_id = $conn->insert_id;

        // STEP B: Insert detailed records into the matching child tables using the generated user_id
        if ($role === 'Student') {
            // Field mapping matches your exact schema schema mapping
            $student_sql = "INSERT INTO student (matric_number, full_name, identification_no, course, phone_number, intern_status, user_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $student_stmt = $conn->prepare($student_sql);
            $student_stmt->bind_param("ssssssi", $matric_number, $full_name, $identification_no, $course, $phone_number, $intern_status, $new_user_id);
            $student_stmt->execute();
        } 
        elseif ($role === 'Lecturer') {
            // Field mapping matches your specific lecturer architecture
            $lecturer_sql = "INSERT INTO lecturer (staff_id, full_name, identification_no, phone_number, user_id) 
                             VALUES (?, ?, ?, ?, ?)";
            $lecturer_stmt = $conn->prepare($lecturer_sql);
            $lecturer_stmt->bind_param("ssssi", $staff_id, $full_name, $identification_no, $phone_number, $new_user_id);
            $lecturer_stmt->execute();
        } 
        elseif ($role === 'Company') {
            // Field mapping matches your corporate structural data layout
            $company_sql = "INSERT INTO company (registration_no, company_name, verification_status, user_id) 
                            VALUES (?, ?, ?, ?)";
            $company_stmt = $conn->prepare($company_sql);
            $company_stmt->bind_param("sssi", $registration_no, $company_name, $verification_status, $new_user_id);
            $company_stmt->execute();
        }

        // Commit all changes to the database simultaneously if no query fails
        $conn->commit();

        // Execution success! Safely redirect to your login layout
        header("Location: ../pages/login.php?signup=success");
        exit();

    } catch (Exception $e) {
        // Rollback structural database states if an unexpected failure occurs
        $conn->rollback();
        
        // Log technical system properties to your private error logs
        error_log("Critical Registration Fail: " . $e->getMessage());
        
        // Route safely to your unified web error template panel
        header("Location: ../pages/login.php?error=system_failure");
        exit();
    }

} else {
    // Block immediate manual direct-URL directory execution access attempts
    header("Location: ../pages/login.php");
    exit();
}