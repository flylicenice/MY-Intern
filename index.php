<?php

session_start();

require_once("includes/db.php");

$sql = "SELECT j.*, c.company_name as company_name
        FROM job_vacancy j 
        JOIN company c ON j.company_id = c.company_id
        WHERE j.status = 'active'";

try {
    $result = $conn->query($sql);
} catch (Exception $e) {
    header("Location: includes/error.php?error=hello");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4d8d735e30.js" crossorigin="anonymous"></script>
    <script src="js/student.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>MYIntern | Home</title>
</head>

<body class="center">

    <?php
    if (isset($_SESSION['user_id'])) {
        include("includes/header_user.php");
    } else {
        include("includes/header_guest.php");
    }
    ?>

    <main class="wrapper">
        <div id="search-bar-container">
            <form class="search-container">
                <input class="basic-textfield " id="job-search" type="search" placeholder="Search Jobs...">

                <input class="basic-textfield" id="location-search" type="search" placeholder="Enter city or region">

                <button class="teal-action-btn" id="go-btn" type="submit">GO</button>
            </form>
        </div>
    </main>

    <div class="main-area" id="job-posting-area">
        <?php
        if ($result->num_rows > 0) {
            while ($job = $result->fetch_assoc()) {
                $title = htmlspecialchars($job['title']);
                $company = htmlspecialchars($job['company_name']);
                $location = htmlspecialchars($job['location_type']);
                $allowance = htmlspecialchars($job['allowance']);
                $desc = $job['description'];
                $job_id = $job['job_id'];
        ?>
                <div class="job-posting-card">
                    <div class="job-details-container">
                        <h3 class="job-posting-title"><?php echo $title; ?></h3>
                        <p class="company-name-text"><?php echo $company; ?></p>
                        <p class="job-location-text"><?php echo $location; ?></p>
                        <p class="job-salary-text">RM <?php echo $allowance; ?> per month</p>
                    </div>

                    <button class="teal-action-btn apply-now-btn" data-job-id="<?php echo $job_id; ?>">Apply</button>
                </div>
        <?php
            }
        } else {
            echo "<p>No active job vacancies at the moment.</p>";
        }
        ?>
    </div>

    <div class="pagination-wrapper-container">
        <p>Pages</p>
    </div>

    <?php include("includes/footer.php"); ?>

    <?php if (isset($_SESSION['matric_number'])): ?>
        <div class="job-panel-overlay">
            <div class="job-details-panel" id="detailsPanel">
                <h3 id="panel-title"></h3>
                <p id="panel-company"></p>
                <p id="panel-location"></p>
                <p id="panel-allowance"></p>
                <div class="job-posting-description" id="panel-description"></div>

                <button class="teal-action-btn apply-now-btn" id="panel-apply-btn" data-job-id="" data-has-resume="<?php echo $_SESSION['has_resume']; ?>">Apply Now</button>
                <button class="action-btn" id="closeDetailsBtn" type="button">&times;</button>
            </div>
        </div>
    <?php else: ?>
        <div class="job-panel-overlay">
            <div class="profile-container">
                <img class="profile-avatar" src="">
            </div>
            <div class="job-details-panel" id="detailsPanel">
                <h3 id="panel-title"></h3>
                <p id="panel-company"></p>
                <p id="panel-location"></p>
                <p id="panel-allowance"></p>

                <div class="job-posting-description" id="panel-description"><?php echo $desc; ?></div>
                <button type="submit" class="teal-action-btn apply-now-btn" id="btn-redirect">Apply Now</button>
                <button class="action-btn" id="closeDetailsBtn" type="button">&times;</button>
            </div>
        </div>

    <?php endif; ?>
</body>

</html>