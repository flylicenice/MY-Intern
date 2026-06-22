<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['company_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized processing access strategy blocked.']);
    exit();
}

require_once("db.php");

$companyId = $_SESSION['company_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName    = trim($_POST['company_name']);
    $employeeSize   = trim($_POST['employee_size']);
    $email          = trim($_POST['email']);
    $unit           = trim($_POST['unit']);
    $street         = trim($_POST['street']);
    $postalCode     = trim($_POST['postal_code']);
    $city           = trim($_POST['city']);
    $atState        = trim($_POST['at_state']);

    if (empty($companyName) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Validation error: Required parameters cannot be left blank.']);
        exit();
    }

    $imageUploaded = isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK;
    $imageBlob = null;

    if ($imageUploaded) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileType    = $_FILES['profile_pic']['type'];
        $fileSize    = $_FILES['profile_pic']['size'];

        if (!in_array($fileType, ['image/jpeg', 'image/jpg', 'image/png'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid picture format. Use JPG, JPEG or PNG templates.']);
            exit();
        }

        if ($fileSize > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'Image weight boundary limit exceeded (Max 2MB allowed).']);
            exit();
        }

        $imageBlob = file_get_contents($fileTmpPath);
    }

    try {
        $conn->begin_transaction();

        $lookupQuery = "SELECT user_id FROM company WHERE company_id = ?";
        $lookupStmt = $conn->prepare($lookupQuery);
        $lookupStmt->bind_param("i", $companyId);
        $lookupStmt->execute();
        $userId = $lookupStmt->get_result()->fetch_assoc()['user_id'] ?? null;

        $userUpdateQuery = "UPDATE user SET email = ? WHERE user_id = ?";
        $userStmt = $conn->prepare($userUpdateQuery);
        $userStmt->bind_param("si", $email, $userId);
        $userStmt->execute();

        if ($imageUploaded) {
            $compUpdateQuery = "UPDATE company SET company_name = ?, employee_size = ?, unit = ?, street = ?, postal_code = ?, city = ?, at_state = ?, profile_pic = ? WHERE company_id = ?";
            $compStmt = $conn->prepare($compUpdateQuery);

            $nullValue = null;
            $compStmt->bind_param("sssssssbi", $companyName, $employeeSize, $unit, $street, $postalCode, $city, $atState, $nullValue, $companyId);
            $compStmt->send_long_data(7, $imageBlob);
        } else {
            $compUpdateQuery = "UPDATE company SET company_name = ?, employee_size = ?, unit = ?, street = ?, postal_code = ?, city = ?, at_state = ? WHERE company_id = ?";
            $compStmt = $conn->prepare($compUpdateQuery);
            $compStmt->bind_param("sssssssi", $companyName, $employeeSize, $unit, $street, $postalCode, $city, $atState, $companyId);
        }

        $compStmt->execute();

        $conn->commit();

        $_SESSION['display_name'] = $companyName;
        echo json_encode(['status' => 'success', 'message' => 'Corporate registration and profile schemas rewritten successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Handoff processing interrupted: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid submission configuration parameters routing template.']);
}
exit();
