<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['matric_number'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized processing access strategy blocked. Please login again.']);
    exit();
}

require_once("db.php");

$matricNo = $_SESSION['matric_number'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $studentName = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
    $phoneNo     = isset($_POST['student_phone']) ? trim($_POST['student_phone']) : '';

    if (empty($studentName)) {
        echo json_encode(['status' => 'error', 'message' => 'Validation error: Name field cannot be left blank.']);
        exit();
    }

    $fileUploaded = isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK;
    $fileBlob = null;

    if ($fileUploaded) {
        $fileTmpPath = $_FILES['resume_file']['tmp_name'];
        $fileType    = $_FILES['resume_file']['type'];
        $fileSize    = $_FILES['resume_file']['size'];

        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($fileType, $allowedMimeTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Use PDF, DOC or DOCX templates.']);
            exit();
        }

        if ($fileSize > 5 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'File limit exceeded (Max 5MB allowed).']);
            exit();
        }

        $fileBlob = file_get_contents($fileTmpPath);
    }

    try {
        $conn->begin_transaction();

        if ($fileUploaded) {
            $studentUpdateQuery = "UPDATE student SET full_name = ?, phone_number = ?, resume = ? WHERE matric_number = ?";
            $studentStmt = $conn->prepare($studentUpdateQuery);

            $nullValue = null;
            $studentStmt->bind_param("ssbs", $studentName, $phoneNo, $nullValue, $matricNo);
            
            $studentStmt->send_long_data(2, $fileBlob);
        } else {
            $studentUpdateQuery = "UPDATE student SET full_name = ?, phone_number = ? WHERE matric_number = ?";
            $studentStmt = $conn->prepare($studentUpdateQuery);
            $studentStmt->bind_param("sss", $studentName, $phoneNo, $matricNo);
        }

        $studentStmt->execute();
        $studentStmt->close();

        $conn->commit();

        if ($fileUploaded) {
            $_SESSION['has_resume'] = 1; 
        }

        echo json_encode(['status' => 'success', 'message' => 'Profile info updated successfully!']);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Action interrupted: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request configuration method.']);
}
exit();
?>