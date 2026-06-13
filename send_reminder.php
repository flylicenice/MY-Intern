<?php
// send_reminder.php  — AJAX endpoint
// Place in: actions/send_reminder.php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$student_id  = isset($data['student_id']) ? (int)$data['student_id'] : 0;
$lecturer_id = (int)$_SESSION['lecturer_id'];

if (!$student_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
    exit;
}

// Verify student belongs to this lecturer
$check = $conn->prepare("SELECT s.student_id, s.full_name, u.email
                          FROM student s JOIN user u ON s.user_id = u.user_id
                          WHERE s.student_id = ? AND s.lecturer_id = ?");
$check->bind_param("ii", $student_id, $lecturer_id);
$check->execute();
$student = $check->get_result()->fetch_assoc();

if (!$student) {
    echo json_encode(['success' => false, 'message' => 'Student not found or not your supervisee']);
    exit;
}

// ── Send email ──
$to      = $student['email'];
$name    = $student['full_name'];
$subject = "MyIntern — Action Required: Submit Your Internship Applications";
$body    = "Dear {$name},\n\n"
         . "Our records show that you have not yet submitted any internship applications on MyIntern.\n\n"
         . "The internship application period is now open. We strongly encourage you to:\n"
         . "  1. Log in to MyIntern at https://myintern.utem.edu.my\n"
         . "  2. Browse available companies under 'Job Listings'\n"
         . "  3. Submit your applications as soon as possible\n\n"
         . "Early applicants have a significantly higher placement rate. Please do not delay.\n\n"
         . "If you are facing any difficulties, please contact your supervisor immediately.\n\n"
         . "Best regards,\nMyIntern — Internship Management System\nUniversiti Teknikal Malaysia Melaka";

$headers = "From: noreply@myintern.utem.edu.my\r\n"
         . "Reply-To: noreply@myintern.utem.edu.my\r\n"
         . "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

// Log the reminder regardless of mail() success in dev environments
$log = $conn->prepare("INSERT INTO reminder_log (student_id, lecturer_id, reminder_type, sent_at)
                        VALUES (?, ?, 'zero_application', NOW())
                        ON DUPLICATE KEY UPDATE sent_at = NOW()");
$log->bind_param("ii", $student_id, $lecturer_id);
$log->execute();

echo json_encode([
    'success' => true,
    'message' => "Reminder sent to {$name} ({$to})"
]);
