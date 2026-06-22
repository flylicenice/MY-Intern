<?php

require_once "includes/session.php";

$loggedInStatus = isLoggedIn();

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
        <?php $i = 0;
        while ($i < 3):
        ?>
            <div class="job-posting-card hide-word-ellipsis">
                <div class="company-logo-container">
                    <img src="assets/default-user.svg" alt="Company Logo" class="company-logo-img">
                </div>

                <div class="job-details-container">
                    <h3 class="job-posting-title">Internship - System Engineer</h3>
                    <p class="company-name-text">Google Sdn. Bhd.</p>
                    <p class="job-location-text">Selangor</p>
                    <p class="job-salary-text">RM 1000 - RM 1500 per month</p>
                    <p class="posted-time-badge">1 month ago</p>
                </div>
            </div>
        <?php $i++;
        endwhile;
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

    <div class="job-details-panel">
        <div class="profile-avatar">
            <img src="assets/default-user.svg" alt="profile-pic" height=40px width=40px>
        </div>

        <div class="details-container">
            <div></div>
            <p class="job-posting-title"><?php echo "Internship - System Engineer" ?></p>

            <div class="job-posting-details">
                <p>Google Sdn. Bhd.</p>
                <p>Location: Selangor</p>
                <p>Allowance: RM 1000 - RM 1500 per month</p>
                <p>Posted a month ago</p>
            </div>
        </div>

        <button class="submit-btn apply-now-btn" data-jobid="1">Apply Now</button>

        <div class="job-posting-description">
            <p><?php $i = 0;
                while ($i < 50) {
                    echo "This is the description and we love it";
                    $i++;
                } ?></p>
        </div>

        <button class="action-btn" id="closeDetailsBtn" type="button">&times; </button>

        <div class="job-company-block">
            
        </div>
    </div>
</body>
</html>