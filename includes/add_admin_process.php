<?php

header('Content-Type: application/json');

include("db.php");

$staffId = htmlspecialchars(trim($_POST['staff_id']), ENT_QUOTES, 'UTF-8');
$adminEmail = htmlspecialchars(trim($_POST["admin_email"]), ENT_QUOTES, 'UTF-8');
$password = trim($_POST["admin_password"]);
$role = "admin";
$status = "active";

try {
    $checkEmail = "SELECT COUNT(*) FROM user WHERE email = ?";
    $insertUserQuery = "INSERT INTO user (email, password, role, status, time_created) VALUES (?, ?, ?, ?, NOW())";
    $insertAdminQuery = "INSERT INTO admin (staff_id, user_id) VALUES (?, ?)";
    //Check Duplicate Email first
    $checkStmt = $conn->prepare($checkEmail);
    $checkStmt->bind_param("s", $adminEmail);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo json_encode([
            "status" => "error",
            "type" => "email_taken",
            "message" => "This email is already taken. Please use another."
        ]);
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    //Insert into database
    $insertUserStmt = $conn->prepare($insertUserQuery);
    $insertUserStmt->bind_param("ssss", $adminEmail, $passwordHash, $role, $status);

    if ($insertUserStmt->execute()) {
        $adminUserId = $conn->insert_id;
        $insertUserStmt->close();

        $insertAdminStmt = $conn->prepare($insertAdminQuery);
        $insertAdminStmt->bind_param("si", $staffId, $adminUserId);

        if ($insertAdminStmt->execute()) {
            $insertAdminStmt->close();
            echo json_encode([
                "status" => "success",
                "message" => "New Admin added successfully!",
            ]);
            exit();
        } else {
            echo json_encode([
                "status" => "error",
                "type" => "admin_insert_fail",
                "message" => "Failed to insert into admin table.",
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "type" => "user_insert_fail",
            "message" => "Failed to insert into user table.",
        ]);
        exit();
    }
} catch (Exception $e) {
    echo json_encode([
            "status" => "error",
            "type" => "system_error",
            "message" => "System Error",
        ]);
    exit();
}
