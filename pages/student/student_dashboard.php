<?php

$user_choice = $_GET['page'] ?? 'application';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/MYIntern/js/script.js"></script>
    <title>MYIntern | Student Dashboard</title>
</head>

<body>
    <?php 
        include("../../includes/header_user.php");
        include("../../includes/student_dashboard_header.php"); 

        if (isset($user_choice) && $user_choice === "application") {
            include("student_application.php");
        } else if (isset($user_choice) && $user_choice === "e-log") {
            include("student_e_log.php");
        } else if (isset($user_choice) && $user_choice === "evaluation") {
            include("student_evaluation.php");
        }

        include("../../includes/footer.php");
    ?>
</body>
</html>