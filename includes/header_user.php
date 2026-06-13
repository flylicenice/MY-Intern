<header>
    <i id="hidden-btn" class="fa-solid fa-bars fa-2sm"></i>
    <h1>MYIntern</h1>
    <nav class="nav-bar">
        <a class="blue-link active" href="/MYIntern/index.php">Job</a>
        <a class="blue-link" href="/MYIntern/pages/student/student_dashboard.php">My Dashboard</a>
    </nav>


    <div class="profile-container" id="profile-trigger">
        <img src="../../assets/default-user.svg" class="profile-avatar" alt="User Profile Picture">

        <svg id="drop-down-profile-arrow" class="dropdown-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 9L12 15L18 9" stroke="#1A2B49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <div class="dropdown-menu" id="profile-menu">
        <a href="/MYIntern/pages/profile.php">Profile</a>
        <hr>
        <a href="/MYIntern/includes/logout.php" style="color: #E53E3E; font-weight: bold;">Sign Out</a>
    </div>
</header>