<main class="dashboard-container">
    <section class="data-table-section">
    <div class="table-header-flex" style="margin-bottom: 1.5rem;">
        <div>
            <h2 class="table-title">My Assigned Interns</h2>
            <p class="total-counter-subtitle">Currently supervising: <strong>6 Students</strong></p>
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
                <tr>
                    <td class="font-bold">D032410113</td>
                    <td class="student-name-cell">TAM KAI DIT</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td>Google Sdn. Bhd.</td>
                    <td>
                        <div class="progress-wrapper">
                            <span class="progress-text">6 of 12 Submitted</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <a href="review_student_logbook.php?student_id=D032410113" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> View Progress Log
                        </a>
                        <a href="review_logbook.php?student_id=D032410155" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> Evaluate
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="font-bold">D032410024</td>
                    <td class="student-name-cell">Adelina Binti Mansor</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td>Nexus Tech Solutions</td>
                    <td>
                        <div class="progress-wrapper">
                            <span class="progress-text">12 of 12 Submitted</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill complete" style="width: 100%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <a href="review_student_logbook.php?student_id=D032410024" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> View Progress Log
                        </a>
                        <a href="review_logbook.php?student_id=D032410155" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> Evaluate
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="font-bold">D032410155</td>
                    <td class="student-name-cell">Lim Wei Jie</td>
                    <td class="text-muted">Diploma Computer Science</td>
                    <td>Intel Corporation</td>
                    <td>
                        <div class="progress-wrapper">
                            <span class="progress-text">2 of 12 Submitted</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill warning" style="width: 16.6%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <a href="review_logbook.php?student_id=D032410155" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> View Progress Log
                        </a>
                        <a href="student_evaluation.php?student_id=D032410155" class="action-view-log-btn">
                            <i class='bx bx-folder-open'></i> Evaluate
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
</main>
