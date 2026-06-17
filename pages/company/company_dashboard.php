<?php
require_once '../../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Company Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <script src="../../js/script.js"></script>
    <script src="../../js/chart.js"></script>
    <link href='../../css/adminstyle.css' rel="stylesheet">
    <link href='../../css/ai.css' rel="stylesheet">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">MYIntern</div>
        <div class="sidebar-subtext">Company</div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="?page=job"><i class='bx bx-task'></i> Job Posting</a>
            </li>
            <li class="nav-item">
                <a href="?page=application"><i class='bx bx-pen'></i> Manage Application</a>
            </li>
            <li class="nav-item logout-box">
                <a href="../../includes/logout.php"><i class='bx bx-log-out'></i> Log Out</a>
            </li>
        </ul>
    </aside>

    <?php
    $currentPage = $_GET['page'] ?? "job";
    if ($currentPage === "main") {
        include("company_stats.php");
    } else if ($currentPage === "job") {
        include("job_posting.php");
    } else if ($currentPage === "application") {
        include("manage_application.php");
    }
    ?>

</body>
</html>