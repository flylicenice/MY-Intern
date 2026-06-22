<?php

if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php");

$firstQuery = "SELECT 
    jv.job_id,
    jv.title AS job_title,
    jv.location_type,
    s.matric_number,
    s.full_name AS student_name,
    u.email AS student_email,
    ja.application_status,
    ja.application_id
    FROM job_application ja
    INNER JOIN job_vacancy jv ON ja.job_id = jv.job_id
    INNER JOIN student s ON ja.matric_number = s.matric_number
    INNER JOIN user u ON u.user_id = s.user_id
    WHERE jv.company_id = ? 
    ORDER BY ja.application_id DESC;";

try {
    $stmt = $conn->prepare($firstQuery);
    $stmt->bind_param("i", $_SESSION['company_id']);
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    header("Location: ../../includes/error.php?error=database_error");
    exit();
}
?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<section class="recruitment-workspace">
    <div class="workspace-header-stack">
        <h2 class="page-title">Manage Applications</h2>
        <p class="page-subtitle">Filter applications by specific active roles to evaluate candidate profiles and update their hiring statuses.</p>
    </div>

    <div class="filter-control-card">
    <label for="job_filter" class="filter-label">Select Job Posting:</label>
    <select id="job_filter" class="main-filter-dropdown">
        <?php if ($result && $result->num_rows > 0): ?>
            <option value="ALL" selected>Show All Job Postings</option>
            
            <?php 
            $displayedJobs = []; 
            
            $result->data_seek(0); 
            while ($row = $result->fetch_assoc()): 
                $jobId = $row['job_id'];

                if (!in_array($jobId, $displayedJobs)): 
                    $displayedJobs[] = $jobId;
            ?>
                    <option value="<?php echo $jobId; ?>">
                        <?php echo htmlspecialchars($row['job_title']); ?>
                    </option>
            <?php 
                endif; 
            endwhile; 
            ?>
            
        <?php else: ?>
            <option selected>No Job Posting</option>
        <?php endif; ?>
    </select>
</div>

    <div class="card-table-container">
        <div class="table-context-indicator">
            Showing candidates for: <strong id="job-indicator">ALL</strong>
        </div>

        <table class="custom-dashboard-table">
            <thead>
                <tr>
                    <th>Matric Number</th>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>Documents</th>
                    <th>Application Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0):
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()):
                        $currentStatus = strtolower($row['application_status']);
                ?>
                        <tr data-app-id="<?php echo $row['application_id']; ?>" data-matric="<?php echo $row['matric_number']; ?>" data-job-id="<?php echo $row['job_id']; ?>">
                            <td class="font-highlight"><?php echo htmlspecialchars($row['matric_number']); ?></td>
                            <td class="font-hightlight"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                            <td>
                                <a href="../../includes/view_resume.php?matric=<?php echo urlencode($row['matric_number']); ?>&appId=<?php echo urlencode($row['application_id']); ?>" target="_blank" class="action-btn">
                                    <i class='bx bxs-file-pdf'></i> View Resume
                                </a>
                            </td>

                            <td>
                                <span class="status-badge <?php echo $currentStatus; ?>">
                                    <?php echo htmlspecialchars($row['application_status']); ?>
                                </span>
                            </td>

                            <td style="text-align: right;">
                                <div class="action-decision-buttons-row">
                                    <?php
                                    $status = isset($row['application_status']) ? trim($row['application_status']) : '';

                                    if (strcasecmp($status, 'Pending') === 0 || strcasecmp($status, 'Viewed') === 0):
                                    ?>
                                        <button type="button"
                                            onclick="approveApplication(this, <?php echo $row['application_id']; ?>, '<?php echo $row['matric_number']; ?>');"
                                            class="btn-micro-approve">
                                            Approve
                                        </button>

                                    <?php else: ?>

                                        <button type="button" class="btn-micro-approve btn-micro-disabled" disabled>
                                            Offered
                                        </button>

                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>