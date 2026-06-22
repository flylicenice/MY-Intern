<?php

require_once '../../includes/db.php';

$total_students = 0;
$status_counts = ['Placed' => 0, 'Still Applying' => 0, 'Not Applying' => 0];

$metric_query = "SELECT intern_status, COUNT(*) as total FROM student GROUP BY intern_status";
$metric_result = $conn->query($metric_query);

if ($metric_result) {
    while ($row = $metric_result->fetch_assoc()) {
        $status = $row['intern_status'];
        $count = (int)$row['total'];
        
        if ($status === 'Placed') {
            $status_counts['Placed'] = $count;
        } elseif ($status === 'Still Applying') {
            $status_counts['Still Applying'] = $count;
        } elseif ($status === 'Not Applying' || $status === 'Inactive') {
            $status_counts['Not Applying'] += $count;
        }
        $total_students += $count;
    }
}

$table_query = "
    SELECT 
        s.matric_number, 
        s.full_name, 
        s.course, 
        s.intern_status,
        CASE 
            WHEN s.intern_status = 'Placed' THEN (
                SELECT c.company_name 
                FROM job_application ja
                JOIN job_vacancy jv ON ja.job_id = jv.job_id
                JOIN company c ON jv.company_id = c.company_id
                WHERE ja.matric_number = s.matric_number AND ja.application_status = 'Approved'
                LIMIT 1
            )
            WHEN s.intern_status = 'Still Applying' THEN (
                SELECT CONCAT(COUNT(*), ' Pending Applications') 
                FROM job_application ja 
                WHERE ja.matric_number = s.matric_number AND ja.application_status = 'Pending'
            )
            ELSE 'No Applications Generated'
        END AS placement_details
    FROM student s
";
$table_result = $conn->query($table_query);

$logbook_query = "
    SELECT 
        s.matric_number, 
        s.full_name, 
        s.course, 
        c.company_name,
        COUNT(l.logbook_id) AS submitted_weeks, 
        12 AS total_weeks
    FROM student s
    JOIN job_application ja ON s.matric_number = ja.matric_number
    JOIN job_vacancy jv ON ja.job_id = jv.job_id
    JOIN company c ON jv.company_id = c.company_id
    JOIN placement p ON ja.application_id = p.application_id 
    LEFT JOIN logbook l ON p.placement_id = l.placement_id 
    WHERE s.intern_status = 'Placed' AND ja.application_status = 'Approved'
    GROUP BY s.matric_number
";
$logbook_result = $conn->query($logbook_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Lecturer Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="../../js/chart.js"></script>
    <script src="../../js/lecturer.js"></script>
    <script src="../../js/script.js"></script>
    <link href='../../css/unistyle.css' rel="stylesheet">
</head>

<body>

    <aside class="sidebar" id="lecturer-sidebar">
        <div class="sidebar-brand">MYIntern</div>
        <div class="sidebar-subtext">Lecturer</div>

        <ul class="nav-menu">
            <li class="nav-item <?php echo (!isset($_GET['page']) || $_GET['page'] === 'main') ? 'active' : ''; ?>">
                <a href="?page=main"><i class='bx bxs-dashboard'></i> Application</a>
            </li>
            <li class="nav-item <?php echo (isset($_GET['page']) && $_GET['page'] === 'logbook') ? 'active' : ''; ?>">
                <a href="?page=logbook"><i class='bx bxs-user-detail'></i> Manage Interns</a>
            </li>
            <li class="nav-item logout-box">
                <a href="../../includes/logout.php"><i class='bx bx-log-out'></i> Log Out</a>
            </li>
        </ul>
    </aside>

    <?php
    $currentPage = $_GET['page'] ?? "main";
    if ($currentPage === "main") {
        include("lecturer_stats.php");
    } else if ($currentPage === "logbook") {
        include("student_logbook.php");
    }
    ?>

</body>
</html>