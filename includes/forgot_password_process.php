<?php

session_start();
require_once("db.php");

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($email) {
    $email = trim($email);
}

try {
    if (!$email || empty($email)) {
        header("Location: ../includes/error.php?error=invalid_email");
        exit();
    }

    $selectQuery = "SELECT COUNT(*) FROM user WHERE email = ?";
    $stmt = $conn->prepare($selectQuery);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $_SESSION['reset_email'] = $email;
            header("Location: ../pages/reset_password.php");
            exit();
        } else {
            header("Location: error.php?error=email_not_found");
            exit();
        }
    } else {
        throw new Exception("Statement preparation failed.");
    }

} catch (Exception $e) {
    header("Location: ../includes/error.php?error=retrieve_error");
    exit();
}