<main class="dashboard-container">

    <div class="header-row">
        <div>
            <h1>Application Overview</h1>
            <p>Welcome back, Madam/Sir</p>
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
                <p class="total-counter-subtitle">Total Students: <?php echo $total_students; ?> Students</p>
            </div>
            
            <div class="status-filter-pills-row">
                <button class="filter-pill active" onclick="filterStatus('ALL')">All</button>
                <button class="filter-pill" onclick="filterStatus('PLACED')">Placed (<?php echo $status_counts['Placed']; ?>)</button>
                <button class="filter-pill" onclick="filterStatus('APPLYING')">Still Applying (<?php echo $status_counts['Still Applying']; ?>)</button>
                <button class="filter-pill danger-pill" onclick="filterStatus('NONE')">Not Applying (<?php echo $status_counts['Not Applying']; ?>)</button>
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
                        <th>Course</th>
                        <th>Placed Company</th>
                        <th>Status</th>
                        <th style="text-align: right; padding-right: 20px;">Action</th>
                    </tr>
                </thead>
<tbody>
    <?php 
    if ($table_result && $table_result->num_rows > 0): 
        while ($row = $table_result->fetch_assoc()): 
            
            $matric = htmlspecialchars($row['matric_number']);
            $name = htmlspecialchars($row['full_name']);
            $course = htmlspecialchars($row['course']);
            $details = htmlspecialchars($row['placement_details']);
            $status = $row['intern_status'];

            if ($status === 'Placed') {
                $data_status = "PLACED";
                $badge_class = "status-placed";
            } elseif ($status === 'Still Applying') {
                $data_status = "APPLYING";
                $badge_class = "status-applying";
            } else {
                // This cleanly catches 'Inactive', 'Not Applying', or any other string safely!
                $data_status = "NONE";
                $badge_class = "status-none";
            }
    ?>
        <tr data-status="<?php echo $data_status; ?>">
            <td class="font-bold"><?php echo $matric; ?></td>
            <td><?php echo $name; ?></td>
            <td class="text-muted"><?php echo $course; ?></td>
            
            <td>
                <?php if ($status === 'Placed'): ?>
                    <?php echo $details; ?>
                <?php elseif ($status === 'Still Applying'): ?>
                    <span class="text-italic text-muted"><?php echo $details; ?></span>
                <?php else: ?>
                    <span class="text-danger font-semibold"><?php echo $details; ?></span>
                <?php endif; ?>
            </td>

            <td><span class="status-pill-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
            
            <td style="text-align: right; padding-right: 20px;">
                <?php if ($status === 'Placed'): ?>
                    <a href="view_student_profile.php?id=<?php echo $matric; ?>" class="action-btn btn-view">Profile</a>
                <?php elseif ($status === 'Still Applying'): ?>
                    <a href="process_application.php?id=<?php echo $matric; ?>&action=approve" class="action-btn btn-approve">Approve</a>
                    <a href="process_application.php?id=<?php echo $matric; ?>&action=reject" class="action-btn btn-reject">Reject</a>
                <?php else: ?>
                    <a href="send_alert.php?id=<?php echo $matric; ?>" class="action-btn btn-send-mail">Send Alert Email</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php 
        endwhile; 
    else: 
    ?>
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No registered student records found.</td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </section>
</main>