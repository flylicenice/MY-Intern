<?php
require_once '../../includes/session.php';
require_once '../../includes/db.php';

// Catch the Matric ID parameter from the URL string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: lecturer_dashboard.php?page=main&status=error&message=Missing+Student+ID");
    exit();
}

$matric = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Fetch Complete Student details
$student_query = "SELECT * FROM student WHERE matric_number = '$matric' LIMIT 1";
$student_result = $conn->query($student_query);

if (!$student_result || $student_result->num_rows === 0) {
    header("Location: lecturer_dashboard.php?page=main&status=error&message=Student+Not+Found");
    exit();
}

$student = $student_result->fetch_assoc();
$placement_details = null;

if ($student['intern_status'] === 'Placed') {
    $placement_query = "
        SELECT p.*, c.company_name
        FROM placement p
        JOIN job_application ja ON p.application_id = ja.application_id
        JOIN job_vacancy jv ON ja.job_id = jv.job_id
        JOIN company c ON jv.company_id = c.company_id
        WHERE ja.matric_number = '$matric' AND ja.application_status = 'Approved'
        LIMIT 1
    ";
    $placement_result = $conn->query($placement_query);
    if ($placement_result && $placement_result->num_rows > 0) {
        $placement_details = $placement_result->fetch_assoc();
        $placement_details['job_title'] = "Industrial Trainee / Intern";
    }
}
    $placement_result = $conn->query($placement_query);
    if ($placement_result && $placement_result->num_rows > 0) {
        $placement_details = $placement_result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | <?php echo htmlspecialchars($student['full_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='../../css/unistyle.css' rel="stylesheet">
    <style>
        .profile-container { max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .profile-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #f1f2f6; padding-bottom: 20px; margin-bottom: 25px; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; color: #1e295d; text-decoration: none; font-weight: 500; }
        .profile-card { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-group { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        .info-label { font-size: 0.85rem; color: #7f8c8d; font-weight: 500; margin-bottom: 5px; text-transform: uppercase; }
        .info-value { font-size: 1.05rem; color: #2c3e50; font-weight: 600; }
        .placement-section { background: #eef2f7; border-left: 5px solid #1e295d; padding: 20px; border-radius: 0 8px 8px 0; }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <div>
            <a href="lecturer_dashboard.php?page=main" class="back-btn"><i class='bx bx-left-arrow-alt'></i> Back to Dashboard</a>
            <h1 style="margin-top: 15px; color: #1e295d;"><?php echo htmlspecialchars($student['full_name']); ?></h1>
        </div>
        <span class="status-pill-badge status-placed" style="padding: 8px 16px; font-size: 0.9rem;"><?php echo htmlspecialchars($student['intern_status']); ?></span>
    </div>

    <div class="profile-card">
        <div class="info-group">
            <div class="info-label">Matric ID</div>
            <div class="info-value"><?php echo htmlspecialchars($student['matric_number']); ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Course Track / Faculty</div>
            <div class="info-value"><?php echo htmlspecialchars($student['course']); ?></div>
        </div>
    </div>

    <?php if ($placement_details): ?>
        <div class="placement-section">
            <h3 style="color: #1e295d; margin-top: 0; margin-bottom: 15px;"><i class='bx bxs-business' style="vertical-align: middle; margin-right: 8px;"></i>Internship Placement Details</h3>
            <div class="profile-card" style="margin-bottom: 0;">
                <div>
                    <div class="info-label">Assigned Company</div>
                    <div class="info-value" style="color: #1e295d;"><?php echo htmlspecialchars($placement_details['company_name']); ?></div>
                </div>
                <div>
                    <div class="info-label">Role / Position</div>
                    <div class="info-value"><?php echo htmlspecialchars($placement_details['job_title']); ?></div>
                </div>
                <div style="margin-top: 10px;">
                    <div class="info-label">Duration Period</div>
                    <div class="info-value" style="font-size: 0.95rem; font-weight: normal;">
                        <?php echo date('d M Y', strtotime($placement_details['start_date'])); ?> to <?php echo date('d M Y', strtotime($placement_details['end_date'])); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="info-group" style="text-align: center; padding: 30px; color: #7f8c8d;">
            <i class='bx bx-info-circle' style="font-size: 2.5rem; margin-bottom: 10px;"></i>
            <p>No active host company placement mappings have been confirmed for this student profile yet.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>