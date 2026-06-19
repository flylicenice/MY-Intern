<?php
require_once __DIR__ . '/../includes/db.php';

if (isset($_GET['code']) && isset($_GET['email'])) {
    $code = trim($_GET['code']);
    $email = trim($_GET['email']);

    try {
        $query_sql = "SELECT user_id FROM user WHERE email = ? AND verification_code = ? AND code_expires_at > NOW() LIMIT 1";
        $stmt = $conn->prepare($query_sql);
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $update_sql = "UPDATE user SET status = 'active', verification_code = NULL, code_expires_at = NULL WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("s", $email);
            $update_stmt->execute();

            header("Location: ../pages/login.php?verification=success");
            exit();
        } else {
            header("Location: ../pages/login.php?verification=expired");
            exit();
        }

    } catch (Exception $e) {
        error_log("Email Link Activation Error: " . $e->getMessage());
        header("Location: ../pages/login.php?verification=system_error");
        exit();
    }
} else {
    header("Location: ../pages/login.php");
    exit();
}   