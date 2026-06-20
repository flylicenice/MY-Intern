<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("db.php");

$admin_email = trim($_POST['email']);
$admin_password = trim($_POST['password']);

$checkQuery = 'SELECT * FROM user u INNER JOIN admin a ON u.user_id = a.user_id WHERE u.email = ?';

try {
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($admin_password, $admin['password'])) {
            $_SESSION['staff_id'] = $admin['staff_id'];
            header("Location: ../pages/admin/admin_dashboard.php?page=main");
            exit();
        } else {
            header("Location: error.php?error=password_wrong");
            exit();
        }
    } else {
        header("Location: error.php?error=not_found");
        exit();
    }
} catch (Exception $e) {
    header("Location: ../error.php?error=system_error");
    exit();
}
