<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
    <title>MYIntern | About Us</title>
</head>
<body class="center">
    <?php include("../includes/header_guest.php") ?>
    
    <div class="blue-container" id="about-container">
        <h1 class="about-title">About Us</h1>
    </div>

    <main class="wrapper">
        <div id="info-wrapper">
            <h1 style="font-size: 4.5rem;">Your Gateway to Professional Excellence</h1>
            <h3 style="font-weight: 400;">A unified tracking platform for students, company partners, and academic advisors.</h3>
        </div>

        <div id="role-wrapper">
            <div class="role-card">
                <div class="role-svg-container">
                    <img class="role-pic" src="../assets/student.svg" alt="student_pic">
                    <h2>Student</h2>
                </div>

                <div class="role-explanation">
                    <p>Search vacancies, track applications and submit reports.</p>
                </div>
            </div>

            <div class="role-card">
                <div class="role-svg-container">
                    <img class="role-pic" src="../assets/deal.svg" alt="deal_pic">
                    <h2>Company</h2>
                </div>

                <div class="role-explanation">
                    <p>Post job postings, review profiles, and evaluate interns digitally.</p>
                </div>
            </div>

            <div class="role-card">
                <div class="role-svg-container">
                    <img class="role-pic" src="../assets/compass.svg" alt="compass_pic">
                    <h2>Student</h2>
                </div>

                <div class="role-explanation">
                    <p>Search vacancies, track applications and submit reports.</p>
                </div>
            </div>
        </div>

        <div id="statistics-wrapper">
            <div class="statistics-card">
                <div class="statistics">
                    <h1 class="stats">100+</h1>
                </div>
                <h2>Active Applicants</h2>
            </div>

            <div class="statistics-card">
                <div class="statistics">
                    <h1 class="stats">100+</h1>
                </div>
                <h2>Partner Companies</h2>
            </div>

            <div class="statistics-card">
                <div class="statistics">
                    <h1 class="stats">50%</h1>
                </div>
                <h2>Success Placements</h2>
            </div>
        </div>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>