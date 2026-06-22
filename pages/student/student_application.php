<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once(dirname(__DIR__, 2) . "/includes/db.php"); 
$db_conn = $conn ?? $db ?? $connect;

// fetch data to display in UI
$user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 0;
$apps_result = null;

$student_stmt = $db_conn->prepare("SELECT matric_number FROM student WHERE user_id = ?");
$student_stmt->bind_param("i", $user_id);
$student_stmt->execute();
$matric_res = $student_stmt->get_result()->fetch_assoc();

if ($matric_res) {
    $matric = $matric_res['matric_number'];
    
    //to get real data
    $sql = "SELECT j.title, c.name as company_name, a.application_status 
            FROM job_application a
            JOIN job_vacancy j ON a.job_id = j.job_id
            JOIN company c ON j.company_id = c.company_id
            WHERE a.matric_number = ?";
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $apps_result = $stmt->get_result();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Student Dashboard</title>
    
    <link rel="stylesheet" href="../../css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<section class="chart-section">
    <div class="metric-card">
        <div class="metric-info">
            <h3>Total Application</h3>
        </div>

        <div class="chart">
            <canvas id="studentApplicationChart"></canvas>
        </div>
    </div>
</section>

<section class="data-table-section">
    <h2 class="table-title">Application Overview</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
              if ($apps_result && $apps_result->num_rows > 0) {
                while ($row = $apps_result->fetch_assoc()) {
                 $status = htmlspecialchars($row['application_status']);
                    echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['company_name']) . "</td>
                    <td><p class='status-badge " . strtolower($status) . "'>{$status}</p></td>
                  </tr>";
                 }
             } else {
        echo "<tr><td class='null-row' colspan='3'>No Applications, Apply Now!</td></tr>";
    }
    ?>

            </tbody>
        </table>
    </div>
</section>

