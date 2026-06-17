<main class="dashboard-container">

        <div class="header-row">
            <div>
                <h1>Application Overview</h1>
                <p>Welcome back, <?php echo "Madam" ?></p>
            </div>
        </div>

        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Total Assigned Interns</h3>
                </div>
                <div class="chart">
                    <canvas id="assignedInternsChart"></canvas>
                </div>
            </div>
        </section>

        <section class="data-table-section">
    <div class="table-header-flex">
        <div>
            <h2 class="table-title">All Students</h2>
            <p class="total-counter-subtitle">Total Students: 180 Students</p>
        </div>
        
        <div class="status-filter-pills-row">
            <button class="filter-pill active" onclick="filterStatus('ALL')">All</button>
            <button class="filter-pill" onclick="filterStatus('PLACED')">Placed (120)</button>
            <button class="filter-pill" onclick="filterStatus('APPLYING')">Still Applying (45)</button>
            <button class="filter-pill danger-pill" onclick="filterStatus('NONE')">Not Applying (15)</button>
        </div>
    </div>

    <div class="top-bar" style="margin-bottom: 1.25rem;">
        <input type="text" id="studentSearchInput" placeholder="Search by student name, matric ID, or course..." onkeyup="searchTable()">
    </div>

    <div class="table-responsive">
        <table id="globalStudentTable">
            <thead>
                <tr>
                    <th>Matric ID</th>
                    <th>Student Name</th>
                    <th>Course / Faculty</th>
                    <th>Placed Company</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 20px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr data-status="PLACED">
                    <td class="font-bold">D032410113</td>
                    <td>TAM KAI DIT</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td>Google Sdn. Bhd.</td>
                    <td><span class="status-pill-badge status-placed">Placed</span></td>
                    <td style="text-align: right; padding-right: 20px;">
                        <a href="view_student_profile.php?id=D032410113" class="action-btn btn-view">Profile</a>
                    </td>
                </tr>

                <tr data-status="APPLYING">
                    <td class="font-bold">D032410095</td>
                    <td>Siti Aminah Binti Ahmad</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td class="text-italic text-muted">3 Pending Applications</td>
                    <td><span class="status-pill-badge status-applying">Still Applying</span></td>
                    <td style="text-align: right; padding-right: 20px;">
                        <a href="view_student_profile.php?id=D032410095" class="action-btn btn-approve">Approve</a>
                        <a href="view_student_profile.php?id=D032410095" class="action-btn btn-reject">Reject</a>
                    </td>
                </tr>

                <tr data-status="NONE">
                    <td class="font-bold">D032410144</td>
                    <td>Muhammad Ariff Bin Zulkifli</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td class="text-danger font-semibold">No Applications Generated</td>
                    <td><span class="status-pill-badge status-none">Not Applying</span></td>
                    <td style="text-align: right; padding-right: 20px;">
                        <a href="view_student_profile.php?id=D032410144" class="action-btn btn-send-mail">Send Alert Email</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
    </main>