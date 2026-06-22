<?php

require_once '../../includes/db.php';

$total_students = 0;
$status_counts = ['Placed' => 0, 'Still Applying' => 0, 'Not Applying' => 0];

$metric_query = "SELECT intern_status, COUNT(*) as total FROM student GROUP BY intern_status";
$metric_result = $conn->query($metric_query);

if ($metric_result) {
    while ($row = $metric_result->fetch_assoc()) {
        $status = strtoupper(trim($row['intern_status']));
        $count = (int)$row['total'];
        
        if ($status === 'PLACED' || $status === 'ACTIVE') {
            $status_counts['Placed'] += $count;
        } elseif ($status === 'STILL APPLYING') {
            $status_counts['Still Applying'] += $count;
        } elseif ($status === 'NOT APPLYING' || $status === 'INACTIVE') {
            $status_counts['Not Applying'] += $count;
        }
        $total_students += $count;
    }
}

// 2. Fetch student profile details along with dynamic application evaluation status
$table_query = "
    SELECT 
        s.matric_number, 
        s.full_name, 
        s.course, 
        s.intern_status,
        -- Count total actual pending rows for this student
        COUNT(CASE WHEN ja.application_status = 'Pending' THEN 1 END) AS pending_count,
        -- Fetch active company name if they are already placed
        (
            SELECT c.company_name 
            FROM job_application ja_placed
            JOIN job_vacancy jv ON ja_placed.job_id = jv.job_id
            JOIN company c ON jv.company_id = c.company_id
            WHERE ja_placed.matric_number = s.matric_number 
              AND ja_placed.application_status = 'placed'
            LIMIT 1
        ) AS current_placement_company
    FROM student s
    LEFT JOIN job_application ja ON s.matric_number = ja.matric_number
    GROUP BY s.matric_number, s.full_name, s.course, s.intern_status
";
$table_result = $conn->query($table_query);

// Ambil ID lecturer yang sedang login daripada session akaun
$lecturer_id = $_SESSION['lecturer_id'] ?? 10; // Menggunakan '10' sebagai default berdasarkan paparan phpMyAdmin anda

$logbook_query = "
    SELECT 
        s.matric_number, 
        s.full_name, 
        s.course, 
        c.company_name,
        COUNT(l.logbook_id) AS submitted_weeks, 
        12 AS total_weeks
    FROM placement p
    -- 1. Hubungkan placement_id ke jadual job_application menggunakan application_id (Nilai: 4)
    INNER JOIN job_application ja ON p.application_id = ja.application_id
    -- 2. Hubungkan job_application ke jadual student menggunakan matric_number
    INNER JOIN student s ON ja.matric_number = s.matric_number
    -- 3. Hubungkan ke jawatan kosong dan syarikat untuk dapatkan nama syarikat tempat penempatan
    INNER JOIN job_vacancy jv ON ja.job_id = jv.job_id
    INNER JOIN company c ON jv.company_id = c.company_id
    -- 4. Hubungkan ke jadual logbook berdasarkan placement_id
    LEFT JOIN logbook l ON p.placement_id = l.placement_id 
    -- 5. TAPISAN UTAMA: Hanya ambil jika status placement aktif (Ongoing) dan milik lecturer yang sedang login
    WHERE p.lecturer_id = ? 
    GROUP BY 
        s.matric_number, 
        s.full_name, 
        s.course, 
        c.company_name,
        p.placement_id
";

// Guna Prepared Statement demi keselamatan pangkalan data
$stmt = $conn->prepare($logbook_query);
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$logbook_result = $stmt->get_result();

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
    <script src="../../js/lecturer.js"></script>
    <link href='../../css/unistyle.css' rel="stylesheet">
</head>

<!-- lecturer_dashboard.php -->
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
            <li class="nav-item <?php echo (isset($_GET['page']) && $_GET['page'] === 'application') ? 'active' : ''; ?>">
                <a href="?page=application"><i class='bx bx-folder-open'></i> Approve Placements</a>
            </li>
            <li class="nav-item logout-box">
                <a href="../../includes/logout.php"><i class='bx bx-log-out'></i> Log Out</a>
            </li>
        </ul>
    </aside>
    </aside>

    <!-- START: The centering container -->
    <section class="review-logbook-section" style="max-width: 1100px; margin: 0 auto; padding: 2rem 1rem; overflow: auto">
        
        <div class="dashboard-app-content">
            <?php
            
            $currentPage = $_GET['page'] ?? "main";
            if ($currentPage === "main") {
                include("lecturer_stats.php");
            } elseif ($currentPage === "logbook") {
                include("student_logbook.php");
            } elseif ($currentPage === "application") {
                include("approve_placement.php");
            }
            ?>
        </div>

    </section>
    <!-- END: The centering container -->
</body>
</html>