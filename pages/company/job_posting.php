<section class="recruitment-workspace">
    <!-- Component Header Action -->
    <div class="workspace-header">
        <div>
            <h2 class="page-title">Manage Job Postings</h2>
            <p class="page-subtitle">Add new vacancies or remove inactive internship listings from the platform.</p>
        </div>
        <button class="btn-primary-action" onclick="toggleJobModal(true)">
            <i class='bx bx-plus-circle'></i> Add New Job Posting
        </button>
    </div>

    <!-- Active Job Postings Table Grid -->
    <div class="card-table-container">
        <table class="custom-dashboard-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Location Type</th>
                    <th>Slots Available</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1 -->
                <tr>
                    <td class="font-highlight">Software Engineer Intern</td>
                    <td>Engineering Team</td>
                    <td><span class="status-badge state-blue">Hybrid</span></td>
                    <td><strong>3 Slots</strong></td>
                    <td style="text-align: right;">
                        <button class="btn-destructive-action" onclick="confirmDeleteJob('Software Engineer Intern')">
                            <i class='bx bx-trash'></i> Remove
                        </button>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr>
                    <td class="font-highlight">UI/UX Designer Intern</td>
                    <td>Product & Creative Design</td>
                    <td><span class="status-badge state-teal">Remote</span></td>
                    <td><strong>1 Slot</strong></td>
                    <td style="text-align: right;">
                        <button class="btn-destructive-action" onclick="confirmDeleteJob('UI/UX Designer Intern')">
                            <i class='bx bx-trash'></i> Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- Pop-up Modal Form Overlay for Adding a New Job -->
<div class="modal-overlay" id="addJobModal" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Create New Internship Slot</h3>
            <button class="close-modal-btn" onclick="toggleJobModal(false)">&times;</button>
        </div>
        
        <form action="process_job_posting.php" method="POST">
            <div class="form-group">
                <label for="job_title">Job Title</label>
                <input type="text" id="job_title" name="job_title" placeholder="e.g. Data Analyst Intern" required>
            </div>
            
            <div class="form-group">
                <label for="description">Job Description</label>
                <textarea id="description" name="description" placeholder="Describe the internship requirements, roles, and responsibilities..." rows="4" required></textarea>
            </div>
            
            <div class="form-row-dual">
                <div class="form-group">
                    <label for="location_type">Location Type</label>
                    <select id="location_type" name="location_type">
                        <option value="On-site">On-site</option>
                        <option value="Remote">Remote</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="allowance">Monthly Allowance (RM)</label>
                    <input type="text" id="allowance" name="allowance" placeholder="e.g. 1000 or Negotiable">
                </div>
            </div>
            
            <div class="form-group">
                <label for="slots">Available Slots</label>
                <input type="number" id="slots" name="slots" min="1" value="1" required>
            </div>
            
            <div class="modal-footer-actions">
                <button type="button" class="btn-cancel" onclick="toggleJobModal(false)">Cancel</button>
                <button type="submit" class="btn-submit-save">Publish Posting</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleJobModal(show) {
        document.getElementById('addJobModal').style.display = show ? 'flex' : 'none';
    }
    function confirmDeleteJob(title) {
        confirm(`Are you sure you want to remove the posting for: "${title}"?`);
    }
</script>