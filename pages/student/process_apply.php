<?php
session_start();
include('../../includes/db_.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    if (!isset($_SESSION['matric_number'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }
        
    $matric_number = $_SESSION['matric_number']; 
    $job_id = intval($_POST['job_id']);

    
    $check_sql = "SELECT application_id FROM job_application WHERE matric_number = ? AND job_id = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $matric_number, $job_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already applied for this position.']);
        exit;
    }


    $sql = "INSERT INTO job_application (matric_number, job_id, application_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $matric_number, $job_id, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}else{
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

?>