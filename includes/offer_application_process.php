<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['company_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized session access denied.']);
    exit();
}

require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_id'])) {

    $appId = intval($_POST['app_id']);
    $matricNo = $_POST['matric_no'];

    $updateQuery = "UPDATE job_application ja
                    INNER JOIN job_vacancy jv ON ja.job_id = jv.job_id
                    SET ja.application_status = 'Offered'
                    WHERE ja.application_id = ? AND jv.company_id = ? AND ja.matric_number = ?";

    try {
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iis", $appId, $_SESSION['company_id'], $matricNo);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Application offered successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No modifications detected. Application might already be offereds.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database exception: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request.'
    ]);
}
exit();
