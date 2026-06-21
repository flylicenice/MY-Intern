<?php

require_once("../../includes/db.php");

try {
    $selectCompanyQuery = "SELECT * FROM user u INNER JOIN company c ON u.user_id = c.user_id WHERE verification_status = 'pending'";
    $result = $conn->query($selectCompanyQuery);
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}
?>

<main class="dashboard-container">

    <div class="header-row">
        <div>
            <h1>System Overview</h1>
            <p>Welcome back, System Administrator</p>
        </div>
    </div>

    <div class="charts-wrapper">
    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>Total Registered Students</h3>
            </div>
            <div class="chart">
                <canvas id="studentChart"></canvas>
            </div>
        </div>
    </section>

    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>All Successful Placements</h3>
            </div>
            <div class="chart">
                <canvas id="placementChart"></canvas>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <h3>Pending Company Approval</h3>
            </div>
            <div class="chart">
                <canvas id="companyChart"></canvas>
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

    <!-- section class="data-table-section">
            <h2 class="table-title">Employer Verification Queue</h2>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Registration No.</th>
                            <th>Location</th>
                            <th>Verification Status</th>
                            <th>Action Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="null-row" colspan="5">
                                No pending action
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo "Google Sdn. Bhd."; ?>
                            </td>
                            <td>
                                <?php echo "Google Sdn. Bhd."; ?>
                            </td>
                            <td>
                                <?php echo "Google Sdn. Bhd."; ?>
                            </td>
                            <td>
                                <p class="status-badge pending">Pending</p>
                            </td>
                            <td>
                                <button id="approve-btn" class="action-btn btn-approve" type="button">Approve</button>
                                <button class="action-btn btn-reject" type="button">Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section -->
</main>