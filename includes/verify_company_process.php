<?php
session_start();
header('Content-Type: application/json');
require_once("db.php");

$company_id = isset($_POST['company_id']) ? intval($_POST['company_id']) : 0;

if ($company_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing Company ID parameter."
    ]);
    exit();
}

$updateQuery = "UPDATE company SET verification_status = 'verified' WHERE company_id = ?";

try {
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $company_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Company successfully marked as verified!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Company was already verified or record could not be located."
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