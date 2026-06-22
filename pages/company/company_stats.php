<?php 

if (!isset($_SESSION['display_name'])) {
    header("Location: ../login.php");
    exit();
}

?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<main class="dashboard-container">

    <div class="header-row">
        <div>
            <h1>Company Overview</h1>
            <p>Welcome back, <?php echo $_SESSION['display_name']; ?></p>
        </div>
    </div>

    <div class="charts-wrapper">
    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>Applications by Role</h3>
            </div>
            <div class="chart">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>
    </section>

    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>Job Application Overview</h3>
            </div>
            <div class="chart">
                <canvas id="placementChart"></canvas>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <h3>Average Company Review</h3>
            </div>
            <div class="chart">
                <canvas id="companyReviewChart"></canvas>
            </div>
        </div>
    </section>
    </div>

    <div class="action-panel-row">
        <button type="button" class="btn-print" id="dashboard-print-trigger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Summary Report
        </button>
    </div>