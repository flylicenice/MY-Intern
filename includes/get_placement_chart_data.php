<?php

header('Content-Type: application/json');
require_once("db.php");


try {
    $query = "SELECT SUM(CASE WHEN status = 'placed' THEN 1 ELSE 0 END) AS placed_count, SUM(CASE WHEN status != 'completed' OR status IS NULL THEN 1 ELSE 0 END) AS completed_count FROM placement";

    $result = $conn->query($query);
    $placed = 0;
    $completed = 0;

    if ($result && $row = $result->fetch_assoc()) {
        $placed = intval($row['placed_count']);
        $completed = intval($row['completed_count']);
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "placed" => $placed,
            "completed" => $completed
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "System error: " . $e->getMessage()
    ]);
}
