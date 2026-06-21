<?php
session_start();
header('Content-Type: application/json');
require_once("db.php");

// 1. Capture payload parameters. If using Matric Number (String), use trim(). If using Student ID (Integer), use intval().
$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';

if (empty($user_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing Student Matric Number parameter."
    ]);
    exit();
}

$updateQuery = "UPDATE user SET status = 'active' WHERE user_id = ?";

try {
    $stmt = $conn->prepare($updateQuery);
    
    $stmt->bind_param("s", $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Student successfully marked as verified!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Student was already verified or record could not be located."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database execution runtime error."
        ]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System exception error: " . $e->getMessage()
    ]);
}
?>