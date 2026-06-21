<?php

session_start();

if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="../../js/admin.js"></script>
    <link href='../../css/unistyle.css' rel="stylesheet">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">MYIntern</div>
        <div class="sidebar-subtext">Admin</div>

        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="?page=main"><i class='bx bxs-dashboard'></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="?page=student"><i class='bx bxs-user-detail'></i> Manage Students</a>
            </li>
            <li class="nav-item">
                <a href="?page=lecturer"><i class='bx bxs-graduation'></i> Manage Lecturers</a>
            </li>
            <li class="nav-item">
                <a href="?page=employer"><i class='bx bxs-briefcase'></i> Verify Company</a>
            </li>
            <li class="nav-item">
                <a href="?page=admin"><i class='bx bxs-user'></i> Add Admin</a>
            </li>
            <li class="nav-item logout-box">
                <a href="../../includes/logout.php"><i class='bx bx-log-out'></i> Log Out</a>
            </li>
        </ul>
    </aside>

    <?php 
    $currentPage = $_GET['page'] ?? "main";
        if ($currentPage === "main") {
            include("admin_stats.php");
        } else if ($currentPage === "student") {
            include("manage_student.php");
        } else if ($currentPage === "lecturer") {
            include("manage_lecturers.php");
        } else if ($currentPage === "employer") {
            include("manage_company.php");
        } else if ($currentPage === "admin") {
            include("manage_admin.php");
        }
    ?>

</body>
</html>