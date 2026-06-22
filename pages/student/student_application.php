<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['job_id'])) {
    
    if(ob_get_length()){
        ob_clean();
    }
    header('Content-Type: application/json');

    // to include database connection file
    include_once(dirname(__DIR__, 2) . "/includes/db.php");
    $db_conn = $conn ?? $db ?? $connect;

    
    if (!isset($db_conn)) {
       echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }

        $jobId = intval($_POST['job_id']);
        $user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 1;

        if ($jobId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Job Identification code received.']);
        exit();
    }

        $student_query = "SELECT matric_number FROM student WHERE user_id = ? LIMIT 1";
        $s_stmt = $db_conn->prepare($student_query);
        $s_stmt->bind_param("i", $user_id);
        $s_stmt->execute();
        $student_res = $s_stmt->get_result()->fetch_assoc();
        
        if (!$student_res) {
            echo json_encode(['status' => 'error', 'message' => 'No student record found for this session.']);
            exit();
        }

        $matric_number = $student_res['matric_number'];

        $vacancy_query = "SELECT job_id FROM job_vacancy WHERE job_id = ? LIMIT 1";
        $v_stmt = $db_conn->prepare($vacancy_query);
        $v_stmt->bind_param("i", $jobId);
        $v_stmt->execute();
        if ($v_stmt->get_result()->num_rows === 0) {
          echo json_encode(['status' => 'error', 'message' => 'The selected vacancy position does not exist in the database catalog.']);
          exit();
        }
      
    $check_query = "SELECT application_id FROM job_application WHERE matric_number = ? AND job_id = ? LIMIT 1";
    $c_stmt = $db_conn->prepare($check_query);
    $c_stmt->bind_param("si", $matric_number, $jobId);
    $c_stmt->execute();
    if ($c_stmt->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already applied for this position!']);
        exit();
    }

    
        $insert_query = "INSERT INTO job_application (matric_number, job_id, status) VALUES (?, ?, 'Pending')";
        $stmt = $db_conn->prepare($insert_query);
        $stmt->bind_param("si", $matric_number, $jobId);
        
        if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Your application has been successfully submitted!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database persistence system fault.']);
    }
   
    exit;
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
                <tr>
                    <td class="null-row" colspan="3">
                        No Application, Apply Now!
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge pending">Pending</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge viewed">Viewed</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge active">Approved</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge viewed">Viewed</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge pending">Pending</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge viewed">Viewed</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge pending">Pending</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo "Internship - HR Digitalisation"; ?>
                    </td>
                    <td>
                        <?php echo "Google Sdn. Bhd."; ?>
                    </td>
                    <td>
                        <p class="status-badge pending">Pending</p>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</section>

