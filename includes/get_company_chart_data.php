<?php

header('Content-Type: application/json');
require_once("db.php");


try {
    $query = "SELECT 
            SUM(CASE WHEN verification_status = 'verified' THEN 1 ELSE 0 END) AS verified_count,
            SUM(CASE WHEN verification_status != 'verified' OR verification_status IS NULL THEN 1 ELSE 0 END) AS unverified_count
            FROM company";

    $result = $conn->query($query);
    $verified = 0;
    $unverified = 0;

    if ($result && $row = $result->fetch_assoc()) {
        $verified = intval($row['verified_count']);
        $unverified = intval($row['unverified_count']);
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "verified" => $verified,
            "unverified" => $unverified
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}
