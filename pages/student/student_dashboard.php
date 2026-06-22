<?php
session_start();
include_once(dirname(__DIR__, 2) . "/includes/db.php");
$user_choice = $_GET['page'] ?? 'application';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $student_stmt = $conn->prepare("SELECT matric_number 
                                    FROM student 
                                    WHERE user_id = ?");
    $student_stmt->bind_param("i", $user_id);
    $student_stmt->execute();
    $matric_res = $student_stmt->get_result()->fetch_assoc();
    
    if ($matric_res) {
        $matric = $matric_res['matric_number'];
        
        $sql = "SELECT j.title, c.name as company_name, a.application_status 
                FROM job_application a
                JOIN job_vacancy j ON a.job_id = j.job_id
                JOIN company c ON j.company_id = c.company_id
                WHERE a.matric_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $apps_result = $stmt->get_result();
    }
}
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
    <link rel="stylesheet" href="/MYIntern/css/style.css">
    <script src="/MYIntern/js/script.js"></script>
    <script src="/MYIntern/js/chart.js"></script>
    <script src="/MYIntern/js/student.js"></script>
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
    <?php
    include("../../includes/footer.php");
    ?>
</body>

</html>