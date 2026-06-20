<?php

// Connect the database first and open the session
require_once '../includes/db.php'; 
require_once '../includes/session.php';

// Only POST method and is submit by the submit_register button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_register'])) {
    
    // Get the value that user input
    $email    = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = trim($_POST['role']); // Student, Lecturer, or Company
    $status = 'pending';

    // Error handling
    if (empty($password) || empty($confirm_password) || empty($role)) {
        header("Location: ../includes/error.php?error=empty_fields");
        exit();
    } 

    if (!$email) {
        header("Location: ../includes/error.php?error=wrong-format");
        exit();
    }

    // Error handling
    if ($password !== $confirm_password) {
        header("Location: ../includes/error.php?error=password_mismatch");
        exit();
    }

    // Error handling
    $allowed_roles = ['Student', 'Company'];
    if (!in_array($role, $allowed_roles)) {
        header("Location: ../includes/error.php?error=invalid_role");
        exit();
    }

    // Get role specific value: Student
    if ($role === 'Student') {
        $matric_number     = trim($_POST['matric_no']);
        $full_name         = trim($_POST['fullname']);
        $identification_no = trim($_POST['ic_no']);
        $course            = trim($_POST['course']);
        $phone_number      = trim($_POST['phone_number']);
        $intern_status     = 'Inactive'; 

        //Error ahndling
        if (empty($matric_number) || empty($full_name) || empty($identification_no) || empty($course) || empty($phone_number)) {
            header("Location: ../includes/error.php?error=empty_student_fields");
            exit();
        }
    }

    // Get role specifiv value: Company
    elseif ($role === 'Company') {
        $registration_no   = trim($_POST['registration_no']);
        $company_name      = trim($_POST['company_name']);
        $employee_size = trim($_POST['employee_size']);
        $unit = trim($_POST['unit']);
        $street = trim($_POST['street']);
        $postcode = trim($_POST['postcode']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $verification_status = 'pending';

        // Error handling
        if (empty($company_name)) {
            header("Location: ../includes/error.php?error=empty_company_fields");
            exit();
        }
    }

    try {
        $verification_code = bin2hex(random_bytes(32)); 
        $code_expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $verification_link = "http://localhost/MYIntern/includes/verify_email.php?code=" . $verification_code . "&email=" . urlencode($email);
            
        $conn->begin_transaction();

        // Check whether email is taken already
        $check_sql = "SELECT user_id FROM user WHERE email = ? LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            header("Location: ../includes/error.php?error=email_taken");
            exit();
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $user_sql = "INSERT INTO user (email, password, role, status, time_created, verification_code, code_expires_at) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("ssssss", $email, $hashed_password, $role, $status, $verification_code, $code_expires_at);
        $user_stmt->execute();
        
        $new_user_id = $conn->insert_id;

        //Student
        if ($role === 'Student') {
            $student_sql = "INSERT INTO student (matric_number, full_name, identification_no, course, phone_number, intern_status, user_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $student_stmt = $conn->prepare($student_sql);
            $student_stmt->bind_param("ssssssi", $matric_number, $full_name, $identification_no, $course, $phone_number, $intern_status, $new_user_id);
            $student_stmt->execute();
        } 
        //Company
        elseif ($role === 'Company') {
            $company_sql = "INSERT INTO company (registration_no, company_name, employee_size, unit, street, postal_code, city, at_state, verification_status, user_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $company_stmt = $conn->prepare($company_sql);
            $company_stmt->bind_param("sssssssssi", $registration_no, $company_name, $employee_size, $unit, $street, $postcode, $city, $state, $verification_status, $new_user_id);
            $company_stmt->execute();
        }

        $conn->commit();

        require_once "../includes/send_verification_email.php";
        $emailSent = sendVerificationEmail($email, $full_name, $verification_link);

        if ($emailSent) {
            header("Location: ../pages/login.php?signup=success");
            exit();
        } else {
            header("Location: ../includes/error.php?email_error=failed_to_send");
            exit();
        }
    
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../includes/error.php?error=system_failure");
        exit();
    }

} else {
    header("Location: ../pages/login.php");
    exit();
}