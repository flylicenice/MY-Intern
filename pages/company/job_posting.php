<?php

if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

$companyId = $_SESSION['company_id'];
require_once("../../includes/db.php");

$query = "SELECT * FROM job_vacancy WHERE company_id = ?";

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}
?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<section class="recruitment-workspace">
    <div class="workspace-header">
        <div>
            <h2 class="page-title">Manage Job Postings</h2>
            <p class="page-subtitle">Add new vacancies or remove internship listings.</p>
        </div>
        <button class="btn-primary-action" onclick="showJobWindow(true)">
            <i class='bx bx-plus-circle'></i> Add New Job Posting
        </button>
    </div>

    <div class="card-table-container">
        <table class="custom-dashboard-table">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Title</th>
                    <th>Location Type</th>
                    <th>Status</th>
                    <th>Post Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="company-job-row" data-status="<?php echo $row['status']; ?>">
                            <td class="font-highlight"><?php echo $row['job_id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><span class="status-badge state-blue"><?php echo $row['location_type']; ?></span></td>
                            <?php if ($row['status'] === "active"): ?>
                            <td>
                                <span class="status-badge <?php echo $row['status']; ?>"><?php echo strtoupper($row['status']); ?></span>
                            </td>
                            <td><?php echo $row["post_date"]; ?></td>
                            <td style="text-align: right;">
                                <button class="btn-destructive-action" onclick="closeJobPosting(this, <?php echo $row['job_id']; ?>)">
                                    <i class='bx bx-trash'></i> Remove
                                </button>
                            </td>
                            <?php else: ?>
                            <td>
                                <span class="status-badge <?php echo $row['status']; ?>"><?php echo strtoupper($row['status']); ?></span>
                            </td>
                            <td><?php echo $row["post_date"]; ?></td>
                            <td style="text-align: right;">
                                <button class="btn-destructive-action btn-disabled">
                                    <i class='bx bx-trash'></i> Remove
                                </button>
                            </td>
                            <?php endif; ?>
                            
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr id='null-state-row'>
                        <td colspan="6">Create One Job Posting Now!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="modal-overlay" id="addJobForm" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Create New Internship Slot</h3>
            <button class="close-modal-btn" onclick="showJobWindow(false)">&times;</button>
        </div>

        <form id="jobPostingForm" action="process_job_posting.php" method="POST">
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
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="allowance">Monthly Allowance (RM)</label>
                    <input type="text" id="allowance" name="allowance" placeholder="e.g. 1000">
                </div>
            </div>

            <div class="modal-footer-actions">
                <button type="button" class="btn-cancel" onclick="showJobWindow(false)">Cancel</button>
                <button type="submit" class="btn-submit-save" id="publishBtn">Publish Posting</button>
            </div>
        </form>
    </div>
</div>