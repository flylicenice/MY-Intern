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