<?php
// Initialize system context 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Setup response structural payload header content-type format 
header('Content-Type: application/json');

// 2. Authentication Guard
if (!isset($_SESSION['user_id']) || !isset($_SESSION['matric_number'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access block. Please re-authenticate login session.'
    ]);
    exit();
}

require_once("db.php");

// 3. Request validation checks
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['job_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid application process action context request parameters.'
    ]);
    exit();
}

$job_id = intval($_POST['job_id']);
$matric_number = $_SESSION['matric_number'];

// Start SQL ACID transaction parameters block 
$conn->begin_transaction();

try {
    // Phase A: Update target status row directly inside the application index
    $updateAppSql = "UPDATE job_application 
                     SET application_status = 'Accepted' 
                     WHERE job_id = ? 
                       AND matric_number = ? 
                       AND application_status = 'Offered'";
                       
    $appStmt = $conn->prepare($updateAppSql);
    $appStmt->bind_param("is", $job_id, $matric_number);
    $appStmt->execute();

    // Verify row transformation properties
    if ($appStmt->affected_rows === 0) {
        throw new Exception("Unable to update offer status. The record may have expired or was modified.");
    }
    $appStmt->close();

    // Phase B: Cascade user state mutation directly inside student profile table index
    // Commit Transaction tracking changes to storage layout context cleanly
    $conn->commit();

    // Phase C: Update session variable runtime flag reference sync configurations safely

    echo json_encode([
        'success' => true,
        'message' => 'Offer accepted successfully! Your placement has been locked down.'
    ]);

} catch (Exception $e) {
    // Rollback changes on systemic mutation engine error faults
    $conn->rollback();
    
    error_log("Offer Acceptance Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Transaction process exception context: ' . $e->getMessage()
    ]);
} finally {
    $conn->close();
}