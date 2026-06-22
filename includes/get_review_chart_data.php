<?php
session_start();
header('Content-Type: application/json');
require_once("db.php");

if (!isset($_SESSION['company_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session expired. Please login again.'
    ]);
    exit();
}

try {
    $query = "SELECT 
        COUNT(CASE WHEN ROUND(rating) = 5 THEN 1 END) AS star_5,
        COUNT(CASE WHEN ROUND(rating) = 4 THEN 1 END) AS star_4,
        COUNT(CASE WHEN ROUND(rating) = 3 THEN 1 END) AS star_3,
        COUNT(CASE WHEN ROUND(rating) = 2 THEN 1 END) AS star_2,
        COUNT(CASE WHEN ROUND(rating) = 1 THEN 1 END) AS star_1,
        AVG(rating) AS average_rating
    FROM company_review
    WHERE company_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_SESSION['company_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $star5 = $star4 = $star3 = $star2 = $star1 = 0;
    $average = 0.0;

    if ($result && $row = $result->fetch_assoc()) {
        $star5   = intval($row['star_5']);
        $star4   = intval($row['star_4']);
        $star3   = intval($row['star_3']);
        $star2   = intval($row['star_2']);
        $star1   = intval($row['star_1']);
        $average = $row['average_rating'] ? round(floatval($row['average_rating']), 1) : 0.0;
    }

    echo json_encode([
        "status" => "success",
        "average" => $average,
        "data" => [
            "counts" => [$star5, $star4, $star3, $star2, $star1]
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}
exit();