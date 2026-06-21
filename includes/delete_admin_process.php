<?php
session_start();

header('Content-Type: application/json');

require_once("db.php");

$user_id = intval($_POST['user_id']);
$deleteQuery = "DELETE FROM user WHERE user_id = ?";

if ($user_id == intval($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "failed",
        "message" => "Cannot delete self"
    ]);
    exit();
}

try {
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $user_id);

    if ($deleteStmt->execute()) {
        if ($deleteStmt->affected_rows > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Delete Successfully"
            ]);
            exit();
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Delete Failed"
            ]);
            exit();
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error"
        ]);
        exit();
    }
    $deleteStmt->close();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System Error",
    ]);
}
