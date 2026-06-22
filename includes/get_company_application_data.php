<?php

session_start();
header('Content-Type: application/json');
require_once("db.php");

if (!isset($_SESSION['company_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session end. Please login again.'
    ]);
    exit();
}

try {
    $query = "SELECT 
    jv.job_id,
    jv.title AS job_title,
    COUNT(ja.application_id) AS application_count
    FROM job_vacancy jv
    LEFT JOIN job_application ja ON jv.job_id = ja.job_id
    WHERE jv.company_id = ?
    GROUP BY jv.job_id, jv.title
    ORDER BY application_count DESC;";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_SESSION['company_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobPosting = [];
    $applicationCount = [];

    while ($row = $result->fetch_assoc()) {
        $jobPosting[] = $row['job_title'];
        $applicationCount[] = intval($row['application_count']);
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "labels" => $jobPosting,
            "counts" => $applicationCount

        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}
