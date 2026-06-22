<?php 

require_once("db.php");

if (!isset($_GET['matric']) || empty(trim($_GET['matric']))) {
    header("Location: error.php>error=not_found");
    exit();
}

$matricNo = trim($_GET['matric']);
$appId = trim($_GET['appId']);

$query = "SELECT resume, full_name FROM student WHERE matric_number = ?";
$updateQuery = "UPDATE job_application SET application_status = 'Viewed' WHERE application_id = ? AND matric_number = ? AND application_status = 'Pending' ";

try {
    $conn->begin_transaction();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $matricNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (!empty($row['resume'])) {
            $resumeBlob = $row['resume'];
            $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['full_name']);
            $fileName = "Resume_" . $name . ".pdf";

            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("is", $appId, $matricNo);
            $updateStmt->execute();

            $conn->commit();

            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"". $fileName . "\"");
            header("Content-Length: " . strlen($resumeBlob));

            if (ob_get_length()) {
                ob_clean();
            }
            flush();

            echo $resumeBlob;
            exit();
        } else {
            $conn->rollback();
            echo "<script>alert('Notice: This applicant has not uploaded a resume file.'); window.close();</script>";
            exit();
        }
    } else {
        $conn->rollback();
        header("Location: error.php?error=student_not_found");
        exit();
    }
} catch (Exception $e) {
    $conn->rollback();
    header("Location: error.php?error=database_error");
    exit();
}

?>