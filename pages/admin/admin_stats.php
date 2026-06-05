<main class="dashboard-container">

        <div class="header-row">
            <div>
                <h1>System Overview</h1>
                <p>Welcome back, System Administrator</p>
            </div>
        </div>

        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Total Registered Students</h3>
                </div>
                <div class="chart">
                    <canvas id="studentChart"></canvas>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Active Faculty Lecturers</h3>
                </div>
                <div class="chart">
                    <canvas id="lecturerChart"></canvas>
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

        <section class="data-table-section">
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
        </section>
    </main>