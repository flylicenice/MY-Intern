<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_choice = $_GET['page'] ?? 'application';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/student.js"></script>
    <title>MYIntern | Student Dashboard</title>
</head>

<body>
    <?php
    include("../../includes/header_user.php");
    ?>

    <div class="blue-container" id="student-nav-container">
        <div class="link-container">
            <a class="white-link" href="?page=application">Application</a>
            <a class="white-link" href="?page=e-log">E-Log</a>
            <a class="white-link" href="?page=evaluation">Evaluation</a>
        </div>
    </div>

    <main class="main-area">
        <?php
        if (isset($user_choice) && $user_choice === "application") {
            include("student_application.php");
        } else if (isset($user_choice) && $user_choice === "e-log") {
            include("student_e_log.php");
        } else if (isset($user_choice) && $user_choice === "evaluation") {
            include("student_evaluation.php");
        }
        ?>
    </main>

    <?php include("../../includes/footer.php"); ?>

</body>

</html>