<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/adminstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <title>MYIntern | Review Logbook</title>
</head>
<body class="center-center">
    <section class="review-logbook-section">
    
    <!-- 1. Student Context Profile Banner (Read-Only) -->
    <div class="student-context-banner">
        <div class="student-profile-meta">
            <div class="avatar-placeholder">
                <i class='bx bx-user'></i>
            </div>
            <div>
                <h2 class="assigned-student-name">TAM KAI DIT</h2>
                <p class="assigned-student-sub">Matric ID: <span class="font-bold">D032410113</span> &bull; Diploma Computer Science</p>
            </div>
        </div>
    </div>

    <!-- 2. Section Header Title -->
    <div class="section-title-bar">
        <h3 class="component-subtitle">Submitted Logbooks</h3>
        <p class="component-desc-text">View logbooks that have been submitted by students.</p>
    </div>

    <!-- 3. Read-Only Logbook Status Timeline Table -->
    <div class="table-responsive">
        <table class="lecturer-review-table">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 24px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Mock data array simulating weekly submission records for this specific student
                    $weeklyLogs = [
                        1 => ['status' => 'Submitted', 'date' => '12 Mar 2026', 'file' => 'log_w1.pdf'],
                        2 => ['status' => 'Submitted', 'date' => '19 Mar 2026', 'file' => 'log_w2.pdf'],
                        3 => ['status' => 'Submitted', 'date' => '26 Mar 2026', 'file' => 'log_w3.pdf'],
                        4 => ['status' => 'Pending',   'date' => '-',           'file' => null],
                        5 => ['status' => 'Pending',   'date' => '-',           'file' => null],
                    ];

                    foreach ($weeklyLogs as $weekNum => $logData):
                ?>
                <tr>
                    <td class="font-bold table-week-cell">
                        Week <?php echo sprintf("%02d", $weekNum); ?>
                    </td>
                    <td class="text-muted date-cell">
                        <?php echo $logData['date']; ?>
                    </td>
                    <td>
                        <?php if($logData['status'] === 'Submitted'): ?>
                            <span class="status-badge-inline status-submitted">Submitted</span>
                        <?php else: ?>
                            <span class="status-badge-inline status-not-submitted">Not Submitted</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <?php if($logData['status'] === 'Submitted'): ?>
                            <!-- Allowed Action: View Only Mode Link Layout -->
                            <a class="lecturer-view-btn" href="view_pdf_viewer.php?file=<?php echo $logData['file']; ?>" target="_blank">
                                <i class='bx bx-show-alt'></i> View Logbook
                            </a>
                        <?php else: ?>
                            <!-- Disabled State: Student has not uploaded anything yet -->
                            <span class="view-btn-disabled">
                                <i class='bx bx-lock-alt'></i> Unavailable
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</section>
</body>
</html>
