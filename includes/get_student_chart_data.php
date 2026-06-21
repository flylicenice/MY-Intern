<?php

header('Content-Type: application/json');
require_once("db.php");


try {
    $query = "SELECT 
                MONTHNAME(u.time_created) AS month_name, 
                COUNT(s.user_id) AS total_students
              FROM student s
              INNER JOIN user u ON s.user_id = u.user_id
              GROUP BY MONTH(u.time_created), MONTHNAME(u.time_created)
              ORDER BY MONTH(u.time_created) ASC";

    $result = $conn->query($query);
    $months = [];
    $studentCounts = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $months[] = $row['month_name'];
            $studentCounts[] = intval($row['total_students']);
        }
    }

    echo json_encode([
        "status" => "success",
        "labels" => $months,
        "data" => $studentCounts
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}
