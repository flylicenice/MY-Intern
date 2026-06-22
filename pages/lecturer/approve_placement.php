<?php
require_once '../../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$status = $_GET['action'] ?? "";

if ($status === "success") {
    echo "<script>alert('Action Successful');</script>";
}

// Queries only records that have been accepted by the company, waiting for lecturer placement
$query = "SELECT ja.application_id, ja.application_status,
                 s.full_name, s.matric_number, s.course,
                 c.company_name, jv.title
          FROM job_application ja
          JOIN student s ON ja.matric_number = s.matric_number
          LEFT JOIN job_vacancy jv ON ja.job_id = jv.job_id
          LEFT JOIN company c ON jv.company_id = c.company_id
          WHERE LOWER(ja.application_status) = 'accepted'";

$result = $conn->query($query);
?>

<div style="width: 100%; max-width: 1200px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; font-family: 'Google Sans', sans-serif; color: #1e293b;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
            <tr>
                <th style="background-color: #f1f5f9; color: #64748b; padding: 14px 18px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Student Details</th>
                <th style="background-color: #f1f5f9; color: #64748b; padding: 14px 18px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Company & Role</th>
                <th style="background-color: #f1f5f9; color: #64748b; padding: 14px 18px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Current Status</th>
                <th style="background-color: #f1f5f9; color: #64748b; padding: 14px 18px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php 
                $numRows = $result->num_rows;
                $count = 0;
                while ($row = $result->fetch_assoc()): 
                    $count++;
                    $isLast = ($count === $numRows);
                    $borderStyle = $isLast ? "none" : "1px solid #e2e8f0";
                ?>
                    <tr>
                        <td style="padding: 14px 18px; border-bottom: <?php echo $borderStyle; ?>; vertical-align: middle;">
                            <div style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($row['full_name']); ?></div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                                Matric: <?php echo htmlspecialchars($row['matric_number']); ?> &bull; <?php echo htmlspecialchars($row['course']); ?>
                            </div>
                        </td>
                        <td style="padding: 14px 18px; border-bottom: <?php echo $borderStyle; ?>; vertical-align: middle;">
                            <div style="font-weight: 500; color: #1e293b;"><?php echo htmlspecialchars($row['title'] ?? 'N/A'); ?></div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;"><?php echo htmlspecialchars($row['company_name'] ?? 'N/A'); ?></div>
                        </td>
                        <td style="padding: 14px 18px; border-bottom: <?php echo $borderStyle; ?>; vertical-align: middle;">
                            <span style="display: inline-flex; padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; background: #f0fdf4; color: #22c55e;">
                                <?php echo htmlspecialchars($row['application_status']); ?>
                            </span>
                        </td>
                        <td style="padding: 14px 18px; border-bottom: <?php echo $borderStyle; ?>; vertical-align: middle; text-align: right;">
                            <form action="../../includes/lecturer_update_application_process.php" method="POST" style="display: inline-flex; gap: 6px;">
                                <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                
                                <button type="submit" name="status" value="placed" style="padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 4px; background: #2dd4bf; color: white;" onmouseover="this.style.background='#0d9488'" onmouseout="this.style.background='#2dd4bf'">
                                    <i class='bx bx-check'></i> Confirm Placement
                                </button>
                                
                                <button type="submit" name="status" value="rejected" style="padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; background: transparent; color: #64748b; border: 1px solid #e2e8f0;" onmouseover="this.style.background='#ffeeef'; this.style.color='#ef4444'; this.style.borderColor='transparent'" onmouseout="this.style.background='transparent'; this.style.color='#64748b'; this.style.borderColor='#e2e8f0'">
                                    Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; padding: 40px;">
                        No accepted applications.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>