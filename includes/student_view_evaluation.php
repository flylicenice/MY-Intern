<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Access denied.");
}

$evaluation_id = $_GET['evaluation_id'] ?? '';

if (empty($evaluation_id)) {
    die("Error: Missing evaluation ID.");
}

$query = "SELECT evaluation_file FROM evaluation WHERE evaluation_id = ? LIMIT 1";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $evaluation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $pdf_data = $row['evaluation_file'];
        
        if (!empty($pdf_data)) {
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=Evaluation_Report.pdf");
            
            echo $pdf_data;
            exit();
        } else {
            die("Error: The evaluation file is empty.");
        }
    } else {
        die("Error: Evaluation record not found.");
    }
    $stmt->close();
} else {
    die("Error: Failed to prepare database statement.");
}
?>