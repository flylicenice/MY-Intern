<?php 
header('Content-Type: application/json');
include("db.php");

// 1. Collect raw POST inputs safely (Prepared statements handle injection prevention)
$staffId   = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : '';
$full_name = isset($_POST['lecturer_name']) ? trim($_POST['lecturer_name']) : '';
$IC        = isset($_POST['lecturer_ic']) ? trim($_POST['lecturer_ic']) : '';
$email     = isset($_POST['lecturer_email']) ? trim($_POST['lecturer_email']) : '';
$phoneNo   = isset($_POST['lecturer_phone']) ? trim($_POST['lecturer_phone']) : '';
$password  = isset($_POST['lecturer_password']) ? trim($_POST['lecturer_password']) : '';
$role      = "Lecturer";
$status    = "active";

// Basic field validation
if (empty($staffId) || empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Required input fields are missing."
    ]);
    exit();
}

try {
    // 2. CRITICAL FIX: Turn off autocommit and open the transaction pipeline
    $conn->begin_transaction();

    // 3. Look for a duplicate email registration record
    $checkEmail = "SELECT COUNT(*) FROM user WHERE email = ? LIMIT 1";
    $checkStmt  = $conn->prepare($checkEmail);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo json_encode([
            "status" => "error",
            "type" => "email_taken",
            "message" => "This email is already taken. Please use another."
        ]);
        exit();
    }

    // 4. Securely hash the input password cleartext
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // 5. Query execution: Parent 'user' credential table entry
    $insertUserQuery = "INSERT INTO user (email, password, role, status, time_created) VALUES (?, ?, ?, ?, NOW())";
    $insertUserStmt  = $conn->prepare($insertUserQuery);
    $insertUserStmt->bind_param("ssss", $email, $passwordHash, $role, $status);

    if (!$insertUserStmt->execute()) {
        throw new Exception("user_insert_fail");
    }

    // Capture the auto-increment user_id
    $lecturerUserId = $conn->insert_id;
    $insertUserStmt->close();

    // 6. Query execution: Child 'lecturer' profile table entry
    // NOTE: Keep 'phone_number' or 'phone_no' matched precisely to your physical schema layout!
    $insertLecturerQuery = "INSERT INTO lecturer (staff_id, full_name, identification_no, phone_number, user_id) VALUES (?, ?, ?, ?, ?)";
    $insertLecStmt = $conn->prepare($insertLecturerQuery);
    $insertLecStmt->bind_param("ssssi", $staffId, $full_name, $IC, $phoneNo, $lecturerUserId);

    if (!$insertLecStmt->execute()) {
        throw new Exception("lec_insert_fail");
    }
    
    $insertLecStmt->close();

    // 7. CRITICAL FIX: Save all structural table writes permanently at once
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "New Lecturer added successfully!",
    ]);
    exit();

} catch (Exception $e) {
    // 8. Undo any structural writes if an exception/error drops mid-stream
    $conn->rollback();

    // Custom check to see what part of our custom script execution triggers threw the exception flag
    if ($e->getMessage() === "user_insert_fail") {
        $msg = "Failed to insert into user credentials index table.";
    } elseif ($e->getMessage() === "lec_insert_fail") {
        $msg = "Failed to map lecturer details onto profile index directories.";
    } else {
        // Typically handles SQL Errors such as a duplicate row inside a UNIQUE column constraint (e.g., staff_id or identification_no)
        $msg = "Registration Error: Duplicate Staff ID or Identification Number detected.";
    }

    echo json_encode([
        "status" => "error",
        "type" => "system_error",
        "message" => $msg
    ]);
    exit();
}
?>