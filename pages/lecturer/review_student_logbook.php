<?php
require_once '../../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Retrieve the student_id (Matric ID) safely from the URL parameter
$student_id = $_GET['student_id'] ?? '';

if (empty($student_id)) {
    die("Error: Student ID not found.");
}

$student_id = mysqli_real_escape_string($conn, $student_id);

// 2. Fetch student profile details dynamically
$student_query = "SELECT matric_number, full_name, course FROM student WHERE matric_number = '$student_id' LIMIT 1";
$student_res = $conn->query($student_query);

if (!$student_res || $student_res->num_rows == 0) {
    die("Error: Student record does not exist.");
}

$student_info = $student_res->fetch_assoc();

// 3. Locate the active placement record for this student
$placement_query = "
    SELECT p.placement_id 
    FROM placement p
    INNER JOIN job_application ja ON p.application_id = ja.application_id
    WHERE ja.matric_number = '$student_id' AND p.status = 'Ongoing'
    LIMIT 1
";
$placement_res = $conn->query($placement_query);

// Set up a structured 12-week layout array as the default base layout
$weeklyLogs = [];
for ($w = 1; $w <= 12; $w++) {
    $weeklyLogs[$w] = [
        'status' => 'Pending',
        'date' => '-',
        'logbook_id' => null
    ];
}

// 4. If an active placement exists, map out submitted logbook records
if ($placement_res && $placement_res->num_rows > 0) {
    $placement_row = $placement_res->fetch_assoc();
    $placement_id = $placement_row['placement_id'];

    $logbook_query = "SELECT logbook_id, week_number, submitted_at FROM logbook WHERE placement_id = '$placement_id'";
    $logbook_res = $conn->query($logbook_query);

    if ($logbook_res && $logbook_res->num_rows > 0) {
        while ($l_row = $logbook_res->fetch_assoc()) {
            $w_num = (int)$l_row['week_number'];
            // If the row's week number falls inside our 1-12 range, update that week's status details
            if ($w_num >= 1 && $w_num <= 12) {
                $weeklyLogs[$w_num] = [
                    'status' => 'Submitted',
                    'date' => date('d M Y', strtotime($l_row['submitted_at'])),
                    'logbook_id' => $l_row['logbook_id'] // Passed to identify the specific file in your viewer
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/unistyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>MYIntern | Review Logbook</title>
</head>
<body class="center-center">
    <section class="review-logbook-section" style="overflow: auto;">
    
    <div class="student-context-banner">
        <div class="student-profile-meta">
            <div class="avatar-placeholder">
                <i class='bx bx-user'></i>
            </div>
            <div>
                <h2 class="assigned-student-name"><?php echo htmlspecialchars($student_info['full_name']); ?></h2>
                <p class="assigned-student-sub">Matric ID: <span class="font-bold"><?php echo htmlspecialchars($student_info['matric_number']); ?></span> &bull; <?php echo htmlspecialchars($student_info['course']); ?></p>
            </div>
        </div>
    </div>

    <div class="section-title-bar">
        <h3 class="component-subtitle">Submitted Logbooks</h3>
        <p class="component-desc-text">View logbooks that have been submitted by students.</p>
    </div>

    <div class="table-responsive">
        <table class="lecturer-review-table">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 24px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weeklyLogs as $weekNum => $logData): ?>
                <tr>
                    <td class="font-bold table-week-cell">
                        Week <?php echo sprintf("%02d", $weekNum); ?>
                    </td>
                    <td class="text-muted date-cell">
                        <?php echo $logData['date']; ?>
                    </td>
                    <td>
                        <?php if($logData['status'] === 'Submitted'): ?>
                            <span class="status-badge-inline status-submitted">Submitted</span>
                        <?php else: ?>
                            <span class="status-badge-inline status-not-submitted">Not Submitted</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <?php if($logData['status'] === 'Submitted'): ?>
                            <a class="lecturer-view-btn" href="view_pdf_viewer.php?logbook_id=<?php echo $logData['logbook_id']; ?>" target="_blank">
                                <i class='bx bx-show-alt'></i> View Logbook
                            </a>
                        <?php else: ?>
                            <span class="view-btn-disabled">
                                <i class='bx bx-lock-alt'></i> Unavailable
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</section>
</body>
</html>