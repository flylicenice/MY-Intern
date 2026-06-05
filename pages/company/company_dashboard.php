<?php
require_once '../../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <script src="../../js/script.js"></script>
    <link href='../../css/adminstyle.css' rel="stylesheet">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">MYIntern</div>
        <div class="sidebar-subtext">Admin</div>

        <ul class="nav-menu">
            <li class="nav-item active">
                <a   href="?page=main"><i class='bx bxs-dashboard'></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="?page=student"><i class='bx bxs-user-detail'></i> Manage Students</a>
            </li>
            <li class="nav-item">
                <a href="?page=lecturer"><i class='bx bxs-graduation'></i> Manage Lecturers</a>
            </li>
            <li class="nav-item">
                <a href="?page=employer"><i class='bx bxs-briefcase'></i> Verify Employers</a>
            </li>
            <li class="nav-item logout-box">
                <a href="../includes/logout.php"><i class='bx bx-log-out'></i> Log Out</a>
            </li>
        </ul>
    </aside>

    <main class="dashboard-container">
        
        <div class="header-row">
            <div>
                <h1>System Overview</h1>
                <p>Welcome back, System Administrator</p>
            </div>
        </div>

        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Total Registered Students</h3>
                    <p>1</p>
                </div>
                <i class='bx bxs-user metric-icon'></i>
            </div>
            
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Active Faculty Lecturers</h3>
                    <p>2</p>
                </div>
                <i class='bx bxs-graduation metric-icon'></i>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Pending Corporate Approvals</h3>
                    <p>3</p>
                </div>
                <i class='bx bxs-business metric-icon'></i>
            </div>
        </section>

        <section class="data-table-section">
            <h2 class="table-title">Employer Verification Queue</h2>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Registration No.</th>
                            <th>Location Location</th>
                            <th>Verification Status</th>
                            <th>Action Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($company_result && $company_result->num_rows > 0): ?>
                            <?php while($row = $company_result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['company_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['registration_no']); ?></td>
                                    <td><?php echo htmlspecialchars($row['city'] . ', ' . $row['at_state']); ?></td>
                                    <td>
                                        <span class="status-badge pending"><?php echo htmlspecialchars($row['verification_status']); ?></span>
                                    </td>
                                    <td>
                                        <a href="../actions/update_company_status.php?id=<?php echo $row['company_id']; ?>&status=verified" class="action-btn btn-approve">Approve</a>
                                        <a href="../actions/update_company_status.php?id=<?php echo $row['company_id']; ?>&status=rejected" class="action-btn btn-reject" onclick="return confirm('Reject this company profile validation application?');">Reject</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                    🎉 Great work! No corporate approval applications are currently pending.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>