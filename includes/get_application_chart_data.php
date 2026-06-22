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
    COUNT(CASE WHEN LOWER(ja.application_status) = 'pending' THEN 1 END) AS pending_count,
    COUNT(CASE WHEN LOWER(ja.application_status) = 'viewed' THEN 1 END) AS viewed_count,
    COUNT(CASE WHEN LOWER(ja.application_status) = 'offered' THEN 1 END) AS offered_count,
    COUNT(CASE WHEN LOWER(ja.application_status) = 'approved' THEN 1 END) AS approved_count,
    COUNT(CASE WHEN LOWER(ja.application_status) = 'rejected' THEN 1 END) AS rejected_count,
    COUNT(ja.application_id) AS total_applications
    FROM job_application ja
    INNER JOIN job_vacancy j ON ja.job_id = j.job_id
    INNER JOIN company c ON j.company_id = c.company_id
    WHERE c.company_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_SESSION['company_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $pending = $viewed = $offered = $approved = $rejected = 0;

    if ($result && $row = $result->fetch_assoc()) {
        $pending = intval($row['pending_count']);
        $viewed = intval($row['viewed_count']);
        $offered = intval($row['offered_count']);
        $approved = intval($row['approved_count']);
        $rejected = intval($row['rejected_count']);
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "pending" => $pending,
            "viewed" => $viewed,
            "offered" => $offered,
            "approved" => $approved,
            "rejected" => $rejected
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}

?>