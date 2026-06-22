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
    s.start_date,
    s.end_date,
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

$name         = $result["full_name"] ?? '';
$matricNo     = $result["matric_number"] ?? '';
$IC           = $result["identification_no"] ?? '';
$course       = $result["course"] ?? '';
$phoneNo      = $result["phone_number"] ?? '';
$internStatus = $result["intern_status"] ?? 'inactive';
$email        = $result["email"] ?? '';
$startDate    = $result["start_date"] ?? ''; 
$endDate      = $result["end_date"] ?? '';   

// ADDED: Process and pass the resume data
$resumeBlob   = $result["resume"]; 

// Dynamically check if the blob column contains binary data
if (!empty($resumeBlob)) {
    $_SESSION['has_resume'] = 1;
    // Pass a safe tracking source variable down to your layout file
    $hasResumeDocument = true;
} else {
    $_SESSION['has_resume'] = 0;
    $hasResumeDocument = false;
}
?>