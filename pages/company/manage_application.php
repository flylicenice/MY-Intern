<section class="recruitment-workspace">
    <div class="workspace-header-stack">
        <h2 class="page-title">Manage Applications</h2>
        <p class="page-subtitle">Filter applications by specific active roles to evaluate candidate profiles and update their hiring statuses.</p>
    </div>

    <div class="filter-control-card">
        <label for="job_filter" class="filter-label">Select Job Posting:</label>
        <select id="job_filter" class="main-filter-dropdown" onchange="this.form.submit()">
            <option value="job_1" selected>Software Engineer Intern (Kuala Lumpur)</option>
            <option value="job_2">UI/UX Designer Intern (Remote)</option>
        </select>
    </div>

    <div class="card-table-container">
        <div class="table-context-indicator">
            Showing candidates for: <strong>Software Engineer Intern</strong>
        </div>
        
        <table class="custom-dashboard-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>University Matrix</th>
                    <th>Documents</th>
                    <th>Application Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-highlight">TAM KAI DIT</td>
                    <td>
                        <span class="uni-text">Universiti Teknikal Malaysia Melaka</span>
                        <span class="sub-course-text">Diploma Computer Science</span>
                    </td>
                    <td>
                        <a href="view_cv.php?id=D032410113" target="_blank" class="action-btn">
                            <i class='bx bxs-file-pdf'></i> View Resume
                        </a>
                    </td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td style="text-align: right;">
                        <div class="action-decision-buttons-row">
                            <form action="process_hiring_state.php" method="POST" style="display:inline;">
                                <input type="hidden" name="app_id" value="113">
                                <button type="submit" name="decision" value="reject" class="btn-micro-decline">Decline</button>
                                <button type="submit" name="decision" value="approve" class="btn-micro-approve">Approve / Place</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="font-highlight">Arissa </td>
                    <td>
                        <span class="uni-text">Universiti Teknikal Malaysia Melaka</span>
                        <span class="sub-course-text">Diploma Computer Science</span>
                    </td>
                    <td>
                        <a href="view_cv.php?id=D032410024" target="_blank" class="action-btn">
                            <i class='bx bxs-file-pdf'></i> View Resume
                        </a>
                    </td>
                    <td><span class="status-badge state-green">Placed</span></td>
                    <td style="text-align: right;">
                        <span class="action-completed-text">Done</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>