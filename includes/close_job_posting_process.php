<?php 

require_once("db.php");

$query = "UPDATE job_vacancy SET status = 'closed' WHERE job_id = ?";

$jobId = $_POST['job_id'];

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $jobId);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Close posting successfully!'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error'
    ]);
}

?>