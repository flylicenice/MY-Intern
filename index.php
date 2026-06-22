<?php
require_once (__DIR__ . "/includes/db.php");
require_once (__DIR__ . "/includes/functions.php");
$loggedInStatus = isLoggedIn();

$sql = "SELECT j.*, c.company_name as company_name 
        FROM job_vacancy j 
        JOIN company c ON j.company_id = c.company_id";
$result = $db_conn->query($sql);

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
    <link href="assets/logo.svg" type="image/svg+xml" rel="icon">
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <title>MYIntern | Home</title>
</head>

<body class="center">

    <?php
    if ($loggedInStatus) {
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
    // Query to get active jobs joined with their company names
    $sql = "SELECT j.*, c.company_name 
            FROM job_vacancy j 
            JOIN company c ON j.company_id = c.company_id 
            WHERE j.status = 'active'";
            
    $result = $db_conn->query($sql);

    if ($result->num_rows > 0) {
        while ($job = $result->fetch_assoc()) {
            // Use htmlspecialchars to prevent XSS attacks
            $title = htmlspecialchars($job['title']);
            $company = htmlspecialchars($job['company_name']);
            $location = htmlspecialchars($job['location_type']);
            $allowance = htmlspecialchars($job['allowance']);
            $job_id = $job['job_id'];
    ?>
            <div class="job-posting-card">
                <div class="company-logo-container">
                    <img src="assets/default-user.svg" alt="Company Logo" class="company-logo-img">
                </div>
                <div class="job-details-container">
                    <h3 class="job-posting-title"><?php echo $title; ?></h3>
                    <p class="company-name-text"><?php echo $company; ?></p>
                    <p class="job-location-text"><?php echo $location; ?></p>
                    <p class="job-salary-text">RM <?php echo $allowance; ?> per month</p>
                    <button class="apply-now-btn" data-jobid="<?php echo $job_id; ?>">Apply Now</button>
                </div>
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

        <?php $i = 1;
        while ($i < 9): ?>
            <a class="pagination-number" href=""><?php echo $i ?></a>
        <?php $i++;
        endwhile; ?>
    </div>

    <?php include("includes/footer.php"); ?>

    <div class="job-details-panel" id="detailsPanel">
    <h3 id="panel-title"></h3>
    <p id="panel-company"></p>
    <p id="panel-location"></p>
    <p id="panel-allowance"></p>
    
    <button class="submit-btn apply-now-btn" id="panel-apply-btn" data-jobid="">Apply Now</button>

    <div class="job-posting-description" id="panel-description"></div>
    
    <button class="action-btn" id="closeDetailsBtn" type="button">&times;</button>
</div>
</body>
</html>