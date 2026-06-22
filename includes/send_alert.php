<?php
// Prevent any accidental output whitespace before headers
header('Content-Type: application/json');

// Adjust paths based on your architecture. If using Composer, require vendor/autoload.php
// Otherwise, manually include the downloaded PHPMailer source files:
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// Adjust to your actual DB relative path location
require_once 'db.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verify Request Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// 2. Validate and Sanitize Matric Number Input
$matric_number = trim($_POST['matric_number'] ?? '');

if (empty($matric_number)) {
    echo json_encode(['status' => 'error', 'message' => 'Matric number is required.']);
    exit;
}

$matric_number = mysqli_real_escape_string($conn, $matric_number);

// 3. Fetch Student Email & Details via User Relation Mapping
$student_query = "SELECT s.full_name, u.email FROM student s JOIN user u ON s.user_id = u.user_id WHERE matric_number = '$matric_number' LIMIT 1";
$student_res = $conn->query($student_query);

if (!$student_res || $student_res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Student record not found.']);
    exit;
}

$student = $student_res->fetch_assoc();
$student_name = ucwords(strtolower($student['full_name']));
$student_email = $student['email'];

if (empty($student_email)) {
    echo json_encode(['status' => 'error', 'message' => 'Student does not have a registered email address.']);
    exit;
}

// 4. Initialize PHPMailer and Configure SMTP Gateway Engine Pipeline
$mail = new PHPMailer(true);

try {
    // --- Server Settings Configuration ---
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set your SMTP server provider (e.g., smtp.gmail.com)
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'tamkaidit50@gmail.com';                 // SMTP account username
    $mail->Password   = 'rwiz atpz crih itbp';           // SMTP account App Password (not your primary password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable implicit TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to; use 465 for implicit SSL / 587 for TLS

    // --- Recipient Routing Data Map ---
    $mail->setFrom('tamkaidit50@gmail.com', 'MYIntern Portal');
    $mail->addAddress($student_email, $student_name);
    $mail->addReplyTo('no-reply@myintern.edu.my', 'MYIntern Support');

    // --- Content Setup ---
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = "URGENT: Internship Application Status Update Required";
    
    $mail->Body = "
    <html>
    <head>
        <title>MYIntern System Alert</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #334155; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; background-color: #ffffff;'>
            <h2 style='color: #dc3545; margin-top: 0;'>Action Required: Internship Status</h2>
            <p>Dear <strong>{$student_name}</strong> ({$matric_number}),</p>
            
            <p>Our records indicate that you currently have <strong>not generated or completed any placement applications</strong> for your upcoming internship semester.</p>
            
            <p>Securing an internship placement is a mandatory requirement for graduation. Please log into the MYIntern portal immediately to browse available job vacancies and submit your applications.</p>
            
            <div style='margin: 24px 0; text-align: center;'>
                <a href='http://" . $_SERVER['HTTP_HOST'] . "/MYIntern/' style='background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;'>Log In to MYIntern</a>
            </div>
            
            <p style='font-size: 13px; color: #64748b;'>If you have already secured a placement independently outside the portal, please notify your assigned lecturer immediately to manually register your placement.</p>
            
            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;'>
            <p style='font-size: 12px; color: #94a3b8; margin-bottom: 0;'>This is an automated systemic notification sent by your Lecturer via MYIntern Management Portal.</p>
        </div>
    </body>
    </html>
    ";

    // Dispatch Message via Outbound Stream Tunnel Loop
    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Alert notification sent successfully.']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Mail transport failed: {$mail->ErrorInfo}"]);
}
exit;