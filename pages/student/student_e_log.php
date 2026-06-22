<?php
// Start session if not already declared globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication Guard: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// 2. Authorization Guard: Block access if the student's internship status is inactive
if (isset($_SESSION['intern_status']) && strtolower($_SESSION['intern_status']) === 'inactive') {
    echo "<script>
        alert('You have no internship yet. Please apply more!');
        window.location.href = 'student_dashboard.php?error=inactive_status';
    </script>";
    exit();
}
require_once("../../includes/db.php");

$submissions = [];
$total_weeks = 10; // Default fallback threshold
$current_intern_week = 1; // Default fallback for current week tracking

$user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 1;

if (isset($conn)) {
    // 1. Fetching start_date and end_date from the placement table
    $placement_query = "SELECT p.placement_id, s.start_date, s.end_date 
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
            $today = new DateTime(); // Current system date tracking

            // Calculate overall total duration boundaries
            $interval = $date1->diff($date2);
            $total_days = $interval->days;
            $calculated_weeks = ceil($total_days / 7);

            if ($calculated_weeks > 0) {
                $total_weeks = $calculated_weeks;
            }

            // Calculate current week position relative to start date
            if ($today >= $date1) {
                $current_days_diff = $date1->diff($today)->days;
                $current_intern_week = ceil(($current_days_diff + 1) / 7);
            } else {
                $current_intern_week = 0; // Internship hasn't started yet
            }
        }

        // 3. FETCH LOGBOOKS
        $log_query = "SELECT logbook_id, week_number
                      FROM logbook
                      WHERE placement_id = ? 
                      ORDER BY week_number ASC";

        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("i", $real_placement_id);
        $log_stmt->execute();
        $result = $log_stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $submissions[$row['week_number']] = [
                'logbook_id' => $row['logbook_id'],
                'status' => $row['status'] ?? 'Submitted'
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
    
    <?php if (isset($start_date) && isset($end_date)): ?>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">
            <strong>Duration:</strong> <?php echo date('d M Y', strtotime($start_date)); ?> to <?php echo date('d M Y', strtotime($end_date)); ?> (<?php echo $total_weeks; ?> Weeks total) <br>
            <strong>Current Timeline:</strong> Week <?php echo ($current_intern_week > $total_weeks) ? $total_weeks . ' (Completed)' : max(1, $current_intern_week); ?>
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
                // 4. LOOP BOUNDARIES: Dynamically evaluates each row matching target week status
                for ($week = 1; $week <= $total_weeks; $week++):
                    $has_submission = isset($submissions[$week]);
                    $is_future_week = ($week > $current_intern_week);
                    
                    if ($has_submission) {
                        $display_text = "Weekly Logbook Submitted";
                        $status = $submissions[$week]['status'];
                    } else {
                        $display_text = $is_future_week ? "Locked" : "No Logbook has been submitted";
                        $status = "Pending";
                    }
                ?>
                    <tr>
                        <td>
                            Week <?php echo $week; ?>
                            <?php if ($week === (int)$current_intern_week): ?>
                                <span style="font-size: 0.75rem; background: #2dd4bf; color: #fff; padding: 2px 6px; border-radius: 4px; margin-left: 5px; font-weight: bold;">Current</span>
                            <?php endif; ?>
                        </td>
                        <td style="<?php echo $has_submission ? 'color: #0d9488; font-weight: 500;' : ($is_future_week ? 'color: #94a3b8; font-style: italic;' : ''); ?>">
                            <?php echo $display_text; ?>
                        </td>
                        <td>
                            <?php if (strtolower($status) === 'submitted' || strtolower($status) === 'approved'): ?>
                                <p class="status-badge active">Submitted</p>
                            <?php else: ?>
                                <p class="status-badge pending">Pending</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($has_submission): ?>
                                <a href="../../includes/view_pdf_viewer.php?logbook_id=<?php echo $submissions[$week]['logbook_id']; ?>" target="_blank">
                                    <button class="action-btn">View</button>
                                </a>
                            <?php else: ?>
                                <a href="upload_logbook.php?week=<?php echo $week; ?>" style="<?php echo $is_future_week ? 'pointer-events: none;' : ''; ?>">
                                    <button class="action-btn btn-view" <?php echo $is_future_week ? 'disabled style="background-color: #cbd5e1; color: #94a3b8; cursor: not-allowed; border-color: #e2e8f0;"' : ''; ?>>
                                        Upload
                                    </button>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</section>