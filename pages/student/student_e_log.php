<?php
// Start session if not already declared globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php");

$submissions = [];
$total_weeks = 10; // Default fallback threshold in case database dates are missing

$user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 1;

if (isset($conn)) {
    // 1. UPDATED QUERY: Fetching start_date and end_date from the placement table
    $placement_query = "SELECT p.placement_id, p.start_date, p.end_date 
                        FROM placement p
                        JOIN job_application ja ON p.application_id = ja.application_id
                        JOIN student s ON ja.matric_number = s.matric_number
                        WHERE s.user_id = ? LIMIT 1";

    $p_stmt = $conn->prepare($placement_query);
    $p_stmt->bind_param("i", $user_id);
    $p_stmt->execute();
    $p_res = $p_stmt->get_result()->fetch_assoc();

    if ($p_res) {
        $real_placement_id = $p_res['placement_id'];
        $start_date = $p_res['start_date'];
        $end_date = $p_res['end_date'];

        // 2. DYNAMIC WEEK CALCULATION
        if (!empty($start_date) && !empty($end_date)) {
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            
            // Calculate absolute difference between the dates
            $interval = $date1->diff($date2);
            $total_days = $interval->days;

            // Convert days to weeks (ceil handles fractional partial weeks gracefully)
            $calculated_weeks = ceil($total_days / 7);

            // Safety Guard: Ensure sensible timeline boundaries
            if ($calculated_weeks > 0) {
                $total_weeks = $calculated_weeks;
            }
        }

        // 3. FETCH LOGBOOKS
        $log_query = "SELECT week_number, logbook 
                      FROM logbook
                      WHERE placement_id = ? 
                      ORDER BY week_number ASC";

        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("i", $real_placement_id);
        $log_stmt->execute();
        $result = $log_stmt->get_result();

        // FIXED: Replaced procedural mysqli_fetch_assoc with Object-Oriented style matching your $conn wrapper pattern
        while ($row = $result->fetch_assoc()) {
            $submissions[$row['week_number']] = [
                'file_name' => $row['logbook'],
                'status' => 'Submitted'
            ];
        }
    }
}
?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<section class="data-table-section">
    <h2 class="table-title">Activity Logbook</h2>
    
    <!-- Optional: Display dynamic timeline info to the user -->
    <?php if (isset($start_date) && isset($end_date)): ?>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">
            <strong>Duration:</strong> <?php echo date('d M Y', strtotime($start_date)); ?> to <?php echo date('d M Y', strtotime($end_date)); ?> (<?php echo $total_weeks; ?> Weeks total)
        </p>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th>File</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 4. UPDATED LOOP: Bound exactly to the calculated $total_weeks limit
                for ($week = 1; $week <= $total_weeks; $week++):
                    $has_submission = isset($submissions[$week]);
                    $file_name = $has_submission ? $submissions[$week]['file_name'] : "No Logbook has been submitted";
                    $status = $has_submission ? $submissions[$week]['status'] : "Pending";
                ?>
                    <tr>
                        <td>Week <?php echo $week; ?></td>
                        <td><?php echo htmlspecialchars($file_name); ?></td>
                        <td>
                            <?php if (strtolower($status) === 'submitted' || strtolower($status) === 'approved'): ?>
                                <p class="status-badge active">Submitted</p>
                            <?php else: ?>
                                <p class="status-badge pending">Pending</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($has_submission): ?>
                                <a href="/MYIntern/uploads/logbooks/<?php echo urlencode($file_name); ?>" target="_blank">
                                    <button class="action-btn">View</button>
                                </a>
                            <?php else: ?>
                                <a href="upload_logbook.php?week=<?php echo $week; ?>">
                                    <button class="action-btn btn-view">Upload</button>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</section>