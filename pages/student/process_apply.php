<?php
session_start();
require_once('../../includes/db.php'); 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $student_id = $_SESSION['student_id']; // ensure student is log in

    if (!isset($_SESSION['matric_number'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
        exit;
    }
        
    $matric_number = $_SESSION['matric_number']; 
    $job_id = intval($_POST['job_id']);

    $check_stmt = $conn->prepare("SELECT application_id FROM job_application WHERE matric_number = ? AND job_id = ?");
    $check_stmt->bind_param("si", $matric_number, $job_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already applied.']);
        exit;
    }

    $status = 'Pending';
    $stmt = $conn->prepare("INSERT INTO job_application (matric_number, job_id, application_status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $matric_number, $job_id, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Application submitted!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
}
?>