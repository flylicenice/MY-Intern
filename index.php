<?php

require_once "config/db.php";
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
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4d8d735e30.js" crossorigin="anonymous"></script>

    <link href="assets/logo.svg" type="image/svg+xml" rel="icon">
    <script src="js/button.js"></script>
    <script src="js/animation"></script>
    <link rel="stylesheet" href="css/style.css">

    <title>MYIntern | Home</title>
</head>

<body class="center">
    <header>
        <i id="hidden-btn" class="fa-solid fa-bars fa-2sm"></i>
        <h1>MYIntern</h1>
        <nav class="nav-bar">
            <a href="#" class="active">Job</a>
            <a href="#">Company</a>
            <?php if ($loggedInStatus): ?>
                <a href="#">My Dashboard</a>
            <?php else: ?>
                <a href="#">About Us</a>
            <?php endif; ?>
        </nav>


        <div class="profile-container">
            <?php if ($loggedInStatus): ?>
                <img src="assets/black.png" class="profile-avatar" alt="User Profile Picture">

                <svg class="dropdown-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9L12 15L18 9" stroke="#1A2B49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            <?php else: ?>
                <a class="link-btn" href="pages/sign_up.php">Sign Up</a>
                <a class="link-btn" href="pages/login.php">Log In</a>
        </div>
    <?php endif; ?>
    </header>


    <main class="wrapper">
        <div id="search-bar-container">
            <form class="search-container">
                <input class="basic-textfield " id="job-search" type="search" placeholder="Search Jobs...">

                <input class="basic-textfield" id="location-search" type="search" placeholder="Enter city or region">

                <button class="btn" id="go-btn" type="submit">GO</button>
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
                <span class="posted-time-badge">1 month ago</span>
            </div>
        </div>
    <?php $i++;
    endwhile;
    ?>
    </div>


    <?php include("includes/footer.php"); ?>
</body>

</html>