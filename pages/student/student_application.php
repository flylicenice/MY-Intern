<?php

if (!isset($_SESSION['user_id']) || !isset($_SESSION['matric_number'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php");

$query = "SELECT 
    ja.job_id, 
    jv.title, 
    jv.allowance, 
    jv.description, 
    c.company_name, 
    jv.location_type, 
    ja.application_status ,
    s.intern_status
FROM job_application ja 
INNER JOIN student s ON ja.matric_number = s.matric_number 
INNER JOIN job_vacancy jv ON jv.job_id = ja.job_id 
INNER JOIN company c ON c.company_id = jv.company_id 
WHERE s.matric_number = ?";

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['matric_number']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} catch (Exception $e) {
    error_log("Student Dashboard Error: " . $e->getMessage());
    header("Location: ../../includes/error.php?error=database_error");
    exit();
}

?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<section class="chart-section">
    <div class="metric-card">
        <div class="metric-info">
            <h3>Total Application</h3>
        </div>

        <div class="chart">
            <canvas id="studentApplicationChart"></canvas>
        </div>
    </div>
</section>

<section class="data-table-section">
    <h2 class="table-title">Application Overview</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>TITLE</th>
                    <th>COMPANY</th>
                    <th>ALLOWANCE</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="clickable-row"
                            data-id="<?php echo $row['job_id']; ?>"
                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                            data-company="<?php echo htmlspecialchars($row['company_name']); ?>"
                            data-allowance="<?php echo htmlspecialchars($row['allowance']); ?>"
                            data-location="<?php echo htmlspecialchars($row['location_type']); ?>"
                            data-status="<?php echo htmlspecialchars($row['application_status']); ?>"
                            data-description="<?php echo htmlspecialchars($row['description']); ?>">

                            <td><?php echo $row['title']; ?></td>
                            <td><strong><?php echo $row['company_name']; ?></strong></td>
                            <td>RM <?php echo $row['allowance']; ?></td>
                            <td>
                                <p class="status-badge <?php echo strtolower($row['application_status']); ?>"><?php echo strtoupper($row['application_status']); ?></p>
                            </td>
                            <?php if ($row['intern_status'] === "active" || strtolower($row['application_status']) === "pending" || strtolower($row['application_status']) === 'placed' || strtolower($row['application_status']) === "viewed" || strtolower($row['application_status']) === 'placed' || strtolower($row['application_status']) === 'accepted' || strtolower($row['application_status']) === 'rejected'): ?>
                                <td>
                                    <button class="action-btn btn-approve btn-disabled">Accept</button>
                                </td>
                            <?php else: ?>
                                <td>
                                    <button class="action-btn btn-approve" id="acceptOfferBtn">Accept</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td class="null-row" colspan="5" style="text-align: center; padding: 20px;">
                            No Application, Apply Now!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

</div>