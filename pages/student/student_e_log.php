<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(dirname(__DIR__, 2) . "/includes/db.php");
$db_conn = $conn ?? $db ?? $connect; 
$submissions = [];

$user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 1;

if (isset($db_conn)) {
    $placement_query = "SELECT p.placement_id 
                        FROM placement p
                        JOIN job_application ja ON p.application_id = ja.application_id
                        JOIN student s ON ja.matric_number = s.matric_number
                        WHERE s.user_id = ? LIMIT 1";
                        
    $p_stmt = $db_conn->prepare($placement_query);
    $p_stmt->bind_param("i", $user_id);
    $p_stmt->execute();
    $p_res = $p_stmt->get_result()->fetch_assoc();
    
    if ($p_res) {
        $real_placement_id = $p_res['placement_id'];

    $log_query = "SELECT week_number, logbook 
                  FROM logbook
                  WHERE placement_id = ? 
                  ORDER BY week_number ASC";
                  
  $log_stmt = $db_conn->prepare($log_query);
        $log_stmt->bind_param("i", $real_placement_id);
        $log_stmt->execute();
        $result = $log_stmt->get_result();
    
  
        while ($row = mysqli_fetch_assoc($result)) {
            $submissions[$row['week_number']] = [
                'file_name' => $row['logbook'],
                'status' =>  'Submitted'
            ];
        }
    }
}
?>
<section class="data-table-section">
    <h2 class="table-title">Activity Logbook</h2>

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
                for ($week = 1; $week <= 10; $week++): 
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