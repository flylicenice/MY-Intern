<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../../includes/db.php");

// 2. Initialize counters object array for the chart buttons
$status_counts = [
    'Placed' => 0,
    'Still Applying' => 0,
    'Not Applying' => 0
];

// 3. Query to fetch total count dynamics from 'student' table
$count_sql = "SELECT intern_status, COUNT(*) as total FROM student GROUP BY intern_status";
$count_result = $conn->query($count_sql);

$total_students = 0;
if ($count_result && $count_result->num_rows > 0) {
    while ($c_row = $count_result->fetch_assoc()) {
        $status_raw = strtolower(trim($c_row['intern_status']));
        
        // Map database string variations safely to match UI components
        if ($status_raw === 'active' || $status_raw === 'placed') {
            $status_counts['Placed'] += $c_row['total'];
        } elseif ($status_raw === 'still applying') {
            $status_counts['Still Applying'] += $c_row['total'];
        } else {
            // 'inactive' or 'not applying'
            $status_counts['Not Applying'] += $c_row['total'];
        }
        $total_students += $c_row['total'];
    }
}

// 4. Fetch all student records to populate the main table rows
$table_sql = "SELECT 
                matric_number, 
                full_name, 
                course, 
                intern_status,
                -- Left join placement details safely if data exists, otherwise fallback
                IFNULL((SELECT p.status FROM placement p WHERE p.application_id = student.matric_number LIMIT 1), 'No Applications Generated') AS placement_details
              FROM student";
              
$table_result = $conn->query($table_sql);
?>
<main class="dashboard-container">
    <section class="data-table-section">
    <div class="table-header-flex" style="margin-bottom: 1.5rem;">
        <div>
            <h2 class="table-title">My Assigned Interns</h2>
            <!-- Real Row Count from Database Query -->
            <p class="total-counter-subtitle">Currently supervising: <strong><?php echo $logbook_result ? $logbook_result->num_rows : 0; ?> Students</strong></p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="lecturer-students-table">
            <thead>
                <tr>
                    <th>Matric ID</th>
                    <th>Student Name</th>
                    <th>Course Track</th>
                    <th>Host Company</th>
                    <th>Logbook Progress</th>
                    <th style="text-align: right; padding-right: 24px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($logbook_result && $logbook_result->num_rows > 0): 
                    while ($row = $logbook_result->fetch_assoc()): 
                        $matric = htmlspecialchars($row['matric_number']);
                        $name = htmlspecialchars($row['full_name']);
                        $course = htmlspecialchars($row['course']);
                        $company = htmlspecialchars($row['company_name']);
                        
                        // Real progress variables mapped 
                        $submitted = (int)$row['submitted_weeks'];
                        $total_weeks = (int)$row['total_weeks'];
                        
                        // Percentage math calculation
                        $percentage = ($total_weeks > 0) ? ($submitted / $total_weeks) * 100 : 0;

                        // Match your custom CSS classes for the progress bar color
                        $progress_class = "";
                        if ($percentage >= 100) {
                            $progress_class = "complete"; // Your custom green layout color
                        } elseif ($percentage < 30) {
                            $progress_class = "warning";  // Your custom red warning color
                        }
                ?>
                    <tr>
                        <td class="font-bold"><?php echo $matric; ?></td>
                        <td class="student-name-cell"><?php echo $name; ?></td>
                        <td class="text-muted"><?php echo $course; ?></td>
                        <td><?php echo $company; ?></td>
                        <td>
                            <div class="progress-wrapper">
                                <span class="progress-text"><?php echo $submitted . " of " . $total_weeks; ?> Submitted</span>
                                <div class="progress-bar-container">
                                    <!-- Dynamic width and classes applied seamlessly -->
                                    <div class="progress-bar-fill <?php echo $progress_class; ?>" style="width: <?php echo $percentage; ?>%;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right; padding-right: 24px;">
                            <a href="review_student_logbook.php?student_id=<?php echo $matric; ?>" class="action-view-log-btn">
                                <i class='bx bx-folder-open'></i> View Progress Log
                            </a>
                            <a href="student_evaluation.php?student_id=<?php echo $matric; ?>" class="action-view-log-btn">
                                <i class='bx bx-folder-open'></i> Evaluate
                            </a>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;" class="text-muted">You are not supervising any placed interns currently.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
</main>