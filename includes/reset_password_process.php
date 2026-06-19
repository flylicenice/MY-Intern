<?php 

session_start();
require_once("db.php");

$email = $_SESSION['reset_email'];
$firstPassword = trim($_POST["first-password"]);
$secondPassword = trim($_POST["second-password"]);

if (empty($firstPassword) || empty($secondPassword)) {
    header("Location: error.php?error=empty");
    exit();
}

if ($firstPassword === $secondPassword) {
    $updateQuery = "UPDATE user SET password = ? WHERE email = ?";
    $passwordHash = password_hash($firstPassword, PASSWORD_BCRYPT);
    try {
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $passwordHash, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            session_unset();
            session_destroy();
            header("Location: ../pages/login.php");
            exit();
        } else {
            header("Location: error.php?error=reset_error");
            exit();
        }
    } catch (Exception $e) {
        header("Location: error.php?error=system_error");
        exit();
    }
}
?>