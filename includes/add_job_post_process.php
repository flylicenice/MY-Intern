<?php

header('Content-Type: application/json');
include("db.php");
session_start();

if (!isset($_SESSION['company_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please log in again."
    ]);
    exit();
}

$title = trim($_POST['job_title']);
$desc = trim($_POST['description']);
$allowance = floatval(trim($_POST['allowance']));
$workType = trim($_POST['location_type']);
$company_id = intval($_SESSION["company_id"]);

try {
    $insertJobQuery = "INSERT INTO job_vacancy (title, description, allowance, location_type, post_date, status, company_id) VALUES (?, ?, ?, ?, NOW(), 'active', ?)";

    $insertQuery = $conn->prepare($insertJobQuery);
    $insertQuery->bind_param("ssdsi", $title, $desc, $allowance, $workType, $company_id);

    if ($insertQuery->execute()) {
        $insertQuery->close();

        echo json_encode([
            "status" => "success",
            "message" => "Job vacancy successfully created and published!"
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to insert into job_vacancy table.",
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
