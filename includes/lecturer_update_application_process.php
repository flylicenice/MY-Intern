<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication & Role Guard
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Safely capture post parameters
    $application_id = $_POST['application_id'] ?? '';
    $action_status  = $_POST['status'] ?? ''; // Expecting either 'Placed' or 'Rejected'
    $lecturer_id    = $_SESSION['lecturer_id'] ?? ''; 

    if (empty($application_id) || empty($action_status) || empty($lecturer_id)) {
        die(json_encode(['status' => 'error', 'message' => 'Missing required fields.']));
    }

    // 3. Begin Database Transaction to maintain data integrity
    $conn->begin_transaction();

    try {
        // Step A: Update the application status to 'Placed' or 'Rejected'
        $update_app_query = "UPDATE job_application SET application_status = ? WHERE application_id = ?";
        $app_stmt = $conn->prepare($update_app_query);
        $app_stmt->bind_param("si", $action_status, $application_id);
        $app_stmt->execute();

        // Step B: If the action taken was 'Placed', initialize placement and update student status
        if (strtolower($action_status) === 'placed') {
            
            // 1. Fetch the student's matric_number linked to this application
            $student_query = "SELECT matric_number FROM job_application WHERE application_id = ? LIMIT 1";
            $student_stmt = $conn->prepare($student_query);
            $student_stmt->bind_param("i", $application_id);
            $student_stmt->execute();
            $student_res = $student_stmt->get_result();
            $student_data = $student_res->fetch_assoc();
            
            if ($student_data) {
                $matric_number = $student_data['matric_number'];

                // 2. Check if a placement entry already exists to avoid duplication
                $check_query = "SELECT placement_id FROM placement WHERE application_id = ? LIMIT 1";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("i", $application_id);
                $check_stmt->execute();
                $check_res = $check_stmt->get_result();

                if ($check_res->num_rows === 0) {
                    $initial_status = 'Ongoing';

                    $insert_placement_query = "INSERT INTO placement (lecturer_id, application_id, status) 
                                               VALUES (?, ?, ?)";
                    
                    $placement_stmt = $conn->prepare($insert_placement_query);
                    $placement_stmt->bind_param("iis", $lecturer_id, $application_id, $initial_status);
                    $placement_stmt->execute();
                }

                // Step C: Update the student's intern status to 'Active'
                // Change 'intern_status' if your student table uses a different column name (e.g., status)
                $update_student_query = "UPDATE student SET intern_status = 'active' WHERE matric_number = ?";
                $student_update_stmt = $conn->prepare($update_student_query);
                $student_update_stmt->bind_param("s", $matric_number);
                $student_update_stmt->execute();
            }
        }

        // Commit changes if everything went well
        $conn->commit();
        
        // Redirect back to your UI layout overview
        header("Location: ../pages/lecturer/lecturer_dashboard.php?page=application&action=success");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Status change operation failure: " . $e->getMessage());
        die("Error: Failed to process application state modifications.");
    }
} else {
    die("Invalid request method.");
}