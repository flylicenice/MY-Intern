<?php
session_start();
include_once(dirname(__DIR__, 2) . "/includes/db.php"); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_SESSION['company_id'] ?? null;
    
    if (!$company_id) {
        die("Error: You must be logged in as a company to post a job.");
    }

    $title = $_POST['job_title'];
    $department = $_POST['department'];
    $location = $_POST['location_type'];
    $slots = intval($_POST['slots_available']);
    $stipend = $_POST['stipend'];
    $description = $_POST['job_description'];

    $sql = "INSERT INTO job_vacancy (title, description, allowance, location_type, post_date, status, company_id)        VALUES (?, ?, ?, ?, ?, ?, ?, 'Active', NOW())";

    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $stipend, $location, $company_id);

    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href='company_dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>