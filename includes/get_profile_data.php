<?php

require_once("db.php");

try {
    $selectQuery = "SELECT 
    s.full_name,
    s.matric_number,
    s.identification_no,
    s.course,
    s.phone_number,
    s.intern_status,
    s.resume,
    u.email
    FROM user u
    INNER JOIN student s ON u.user_id = s.user_id
    WHERE u.user_id = ? LIMIT 1";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $_SESSION['user_id']);
    $selectStmt->execute();

    $row = $selectStmt->get_result();
    $result = $row->fetch_assoc();
} catch (Exception $e) {
    header("Location: ../pages/error.php?error=database-error");
    exit();
} finally {
    $conn->close();
}

$name = $result["full_name"];
$matricNo = $result["matric_number"];
$IC = $result["identification_no"];
$course = $result["course"];
$phoneNo = $result["phone_number"];
$internStatus = $result["intern_status"];
$email = $result["email"];
?>