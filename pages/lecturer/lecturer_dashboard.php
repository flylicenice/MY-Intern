<?php
require_once '../../includes/session.php';
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
    <script src="../../js/script.js"></script>
    <link href='../../css/unistyle.css' rel="stylesheet">
</head>

<body>

    <aside class="sidebar" id="lecturer-sidebar">
        <div class="sidebar-brand">MYIntern</div>
        <div class="sidebar-subtext">Lecturer</div>

        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="?page=main"><i class='bx bxs-dashboard'></i> Application</a>
            </li>
            <li class="nav-item">
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