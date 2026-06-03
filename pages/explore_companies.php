<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

$loggedInStatus = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Explore Companies</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/4d8d735e30.js" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="../css/style.css">
    <script src="js/button.js"></script>
</head>
<body class="center">

    <?php 
        if (!$loggedInStatus) {
            include("../includes/header_guest.php");
        } else {
            include("../includes/header_user.php");
        }
    ?> 

    <div id="search-bar-container" class="company-search-banner">
        <div class="wrapper">
            <form class="search-container single-search" method="GET">
                <input class="basic-textfield" id="company-search" name="search" type="search" placeholder="Search Company...">
            </form>
        </div>
    </div>



    <?php include("../includes/footer.php"); ?>

</body>
</html>