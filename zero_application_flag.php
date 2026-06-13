<?php
// zero_application_flag.php
// Lists students with no applications submitted; allows sending reminder notifications
// Place in: pages/lecturer/zero_application_flag.php

session_start();
include '../../includes/lecturer_dashboard_header.php';
include '../../config/db.php';

$lecturer_id = $_SESSION['lecturer_id'];

// Fetch students under this lecturer with zero applications
$sql = "
    SELECT
        s.student_id,
        s.full_name,
        s.matric_no,
        s.phone_number,
        u.email,
        DATEDIFF(CURDATE(), s.enrollment_date) AS days_enrolled,
        s.enrollment_date,
        COALESCE(r.reminder_sent, 0) AS reminder_sent,
        r.last_reminder_date
    FROM student s
    JOIN user u ON s.user_id = u.user_id
    LEFT JOIN (
        SELECT student_id, COUNT(*) AS total_apps
        FROM application
        GROUP BY student_id
    ) app ON s.student_id = app.student_id
    LEFT JOIN (
        SELECT student_id, 1 AS reminder_sent, MAX(sent_at) AS last_reminder_date
        FROM reminder_log
        WHERE reminder_type = 'zero_application'
        GROUP BY student_id
    ) r ON s.student_id = r.student_id
    WHERE s.lecturer_id = ?
      AND (app.total_apps IS NULL OR app.total_apps = 0)
    ORDER BY s.enrollment_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();
$flagged = $result->fetch_all(MYSQLI_ASSOC);

$total_flagged = count($flagged);

// Handle bulk remind POST
$bulk_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'remind_all') {
        // In production: trigger email to each flagged student
        $ids = array_column($flagged, 'student_id');
        foreach ($ids as $sid) {
            // Log the reminder
            $log_sql = "INSERT INTO reminder_log (student_id, lecturer_id, reminder_type, sent_at)
                        VALUES (?,?,'zero_application', NOW())
                        ON DUPLICATE KEY UPDATE sent_at = NOW()";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("ii", $sid, $lecturer_id);
            $log_stmt->execute();
        }
        $bulk_msg = "Reminders sent to all " . count($ids) . " flagged student(s).";
    } elseif ($_POST['action'] === 'remind_one' && isset($_POST['student_id'])) {
        $sid = (int)$_POST['student_id'];
        $log_sql = "INSERT INTO reminder_log (student_id, lecturer_id, reminder_type, sent_at)
                    VALUES (?,?,'zero_application', NOW())
                    ON DUPLICATE KEY UPDATE sent_at = NOW()";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ii", $sid, $lecturer_id);
        $log_stmt->execute();
        $bulk_msg = "Reminder sent successfully.";
    }
    // Re-fetch after update
    $stmt->execute();
    $flagged = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $total_flagged = count($flagged);
}

// Urgency levels: days enrolled without application
function urgencyLevel(int $days): array {
    if ($days >= 30) return ['label' => 'Critical', 'class' => 'urg-critical'];
    if ($days >= 14) return ['label' => 'High',     'class' => 'urg-high'];
    if ($days >= 7)  return ['label' => 'Medium',   'class' => 'urg-medium'];
    return ['label' => 'Low', 'class' => 'urg-low'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zero Application Flagging | MyIntern</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        :root {
            --gold:       #C8A96E;
            --gold-dark:  #A8893E;
            --gold-light: #E8D9B5;
            --gold-bg:    #F5EDD8;
            --dark-header:#3D3D2E;
            --text-main:  #1a1a1a;
            --text-muted: #666;
            --bg-page:    #f7f6f2;
            --bg-white:   #ffffff;
            --border:     #e0d9c8;
            --radius:     6px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg-page); color: var(--text-main); }

        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 24px 20px; }
        .page-title { font-size: 22px; font-weight: 600; color: var(--dark-header); margin-bottom: 4px; }
        .page-subtitle { font-size: 14px; color: var(--text-muted); margin-bottom: 22px; }

        /* Hero alert */
        .hero-alert {
            border-radius: var(--radius);
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 18px;
        }
        .hero-alert.has-flags  { background: #fff3cd; border: 1px solid #f0c040; border-left: 5px solid #e0a800; }
        .hero-alert.no-flags   { background: #d4edda; border: 1px solid #b8ddc8; border-left: 5px solid #28a745; }
        .hero-icon { font-size: 36px; }
        .hero-text h3 { font-size: 17px; font-weight: 700; color: #7a5800; margin-bottom: 4px; }
        .hero-text p  { font-size: 13px; color: #856404; }
        .hero-alert.no-flags .hero-text h3,
        .hero-alert.no-flags .hero-text p { color: #155724; }

        /* Stats row */
        .stats-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 22px; }
        .stat-pill {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-header);
            display: flex; align-items: center; gap: 6px;
        }
        .stat-pill span { font-weight: 400; color: var(--text-muted); }

        /* Toolbar */
        .toolbar { display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; align-items: center; }
        .toolbar input[type="text"] {
            flex: 1; min-width: 180px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px; outline: none;
        }
        .toolbar input:focus { border-color: var(--gold); }
        .btn { padding: 8px 16px; border-radius: var(--radius); font-size: 14px; cursor: pointer; border: none; font-weight: 500; }
        .btn-gold    { background: var(--gold); color: #fff; }
        .btn-gold:hover { background: var(--gold-dark); }
        .btn-danger  { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #b02a37; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-main); }
        .btn-outline:hover { background: var(--gold-bg); }

        /* Bulk action bar */
        .bulk-bar {
            background: var(--dark-header);
            color: #fff;
            padding: 10px 20px;
            border-radius: var(--radius) var(--radius) 0 0;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        }
        .bulk-bar .count { font-size: 14px; }
        .bulk-actions { display: flex; gap: 8px; }
        .bulk-btn {
            padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: pointer;
            border: 1px solid rgba(255,255,255,.3); background: transparent; color: #fff;
        }
        .bulk-btn:hover { background: rgba(255,255,255,.15); }
        .bulk-btn.danger { border-color: #ff8080; color: #ff8080; }

        /* Table */
        .table-wrap { background: var(--bg-white); border-radius: 0 0 var(--radius) var(--radius); border: 1px solid var(--border); border-top: none; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead tr { background: #4a4a38; }
        thead th { padding: 11px 16px; color: #f5e6c8; font-weight: 600; text-align: left; white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #fff8f0; }
        td { padding: 11px 16px; vertical-align: middle; }

        /* Urgency badges */
        .urg-critical { background: #f8d7da; color: #721c24; }
        .urg-high     { background: #ffe4d0; color: #7a3a0b; }
        .urg-medium   { background: #fff3cd; color: #856404; }
        .urg-low      { background: #e8f5e9; color: #155724; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }

        /* Reminder status */
        .badge-sent     { background: #d4edda; color: #155724; }
        .badge-not-sent { background: #f1f1f1; color: #555; }

        /* Days cell */
        .days-critical { font-weight: 700; color: #dc3545; }
        .days-high     { font-weight: 700; color: #fd7e14; }
        .days-normal   { color: var(--text-main); }

        /* Action buttons */
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 4px; border: 1px solid var(--border); background: var(--bg-white); cursor: pointer; color: var(--text-main); }
        .btn-sm:hover { background: var(--gold-bg); border-color: var(--gold); }
        .btn-sm.remind { border-color: #dc3545; color: #dc3545; }
        .btn-sm.remind:hover { background: #fde8e8; }
        .btn-sm.reminded { border-color: #28a745; color: #28a745; cursor: default; }

        /* Success message */
        .msg-success { background: #d4edda; border: 1px solid #b8ddc8; color: #155724; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 16px; font-size: 14px; }

        /* Empty state */
        .empty-state { text-align: center; padding: 50px 20px; }
        .empty-icon { font-size: 48px; margin-bottom: 12px; }
        .empty-state h3 { font-size: 18px; color: #28a745; margin-bottom: 6px; }
        .empty-state p { font-size: 14px; color: var(--text-muted); }

        /* Checkbox */
        .row-check { width: 16px; height: 16px; accent-color: var(--gold); cursor: pointer; }

        /* Popover tooltip for days */
        .progress-bar-wrap { background: #eee; border-radius: 4px; height: 6px; width: 80px; overflow: hidden; display: inline-block; vertical-align: middle; margin-left: 6px; }
        .progress-bar { height: 100%; border-radius: 4px; }
        .pb-red    { background: #dc3545; }
        .pb-orange { background: #fd7e14; }
        .pb-yellow { background: #ffc107; }
        .pb-green  { background: #28a745; }

        /* Back link */
        .btn-back { display: inline-block; margin-bottom: 16px; font-size: 14px; color: var(--gold); text-decoration: none; }
        .btn-back:hover { color: var(--gold-dark); text-decoration: underline; }
    </style>
</head>
<body>
<div class="page-wrap">
    <a class="btn-back" href="student_monitoring.php">← Back to Monitoring Dashboard</a>
    <div class="page-title">⚑ Zero Application Flagging</div>
    <div class="page-subtitle">Students under your supervision who have not submitted any internship application.</div>

    <?php if ($bulk_msg): ?>
    <div class="msg-success">✅ <?= htmlspecialchars($bulk_msg) ?></div>
    <?php endif; ?>

    <!-- Hero alert -->
    <div class="hero-alert <?= $total_flagged > 0 ? 'has-flags' : 'no-flags' ?>">
        <div class="hero-icon"><?= $total_flagged > 0 ? '⚠️' : '✅' ?></div>
        <div class="hero-text">
            <?php if ($total_flagged > 0): ?>
            <h3><?= $total_flagged ?> student(s) have not applied to any internship.</h3>
            <p>Early intervention increases placement rates. Send reminders and track their progress below.</p>
            <?php else: ?>
            <h3>All students have submitted at least one application!</h3>
            <p>Great progress. Continue monitoring their interview and placement status on the main dashboard.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($total_flagged > 0): ?>

    <!-- Stats pills -->
    <div class="stats-row">
        <?php
        $critical = array_filter($flagged, fn($s) => $s['days_enrolled'] >= 30);
        $reminded = array_filter($flagged, fn($s) => $s['reminder_sent']);
        ?>
        <div class="stat-pill">⚑ <?= $total_flagged ?> <span>Flagged</span></div>
        <div class="stat-pill" style="border-color:#dc3545; color:#dc3545;">🔴 <?= count($critical) ?> <span>Critical (30+ days)</span></div>
        <div class="stat-pill" style="border-color:#28a745; color:#28a745;">📧 <?= count($reminded) ?> <span>Reminded</span></div>
        <div class="stat-pill">📋 <?= $total_flagged - count($reminded) ?> <span>Not yet reminded</span></div>
    </div>

    <!-- Toolbar + Bulk Remind All -->
    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="Search by name or matric no...">
        <form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="remind_all">
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Send reminder to ALL <?= $total_flagged ?> flagged students?')">
                📧 Remind All (<?= $total_flagged ?>)
            </button>
        </form>
        <button class="btn btn-outline" onclick="exportTable()">⬇ Export CSV</button>
    </div>

    <!-- Bulk bar + Table -->
    <div class="bulk-bar">
        <div class="count">
            <strong><?= $total_flagged ?></strong> student(s) flagged &mdash; sorted by days enrolled (most urgent first)
        </div>
        <div class="bulk-actions">
            <span style="font-size:13px; opacity:.7;">Select rows to act on individual students</span>
        </div>
    </div>
    <div class="table-wrap">
        <table id="flagTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll" class="row-check" title="Select all"></th>
                    <th>Student Name</th>
                    <th>Matric No</th>
                    <th>Email</th>
                    <th>Days Enrolled</th>
                    <th>Urgency</th>
                    <th>Reminder Status</th>
                    <th>Last Reminded</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            usort($flagged, fn($a, $b) => $b['days_enrolled'] - $a['days_enrolled']);
            foreach ($flagged as $st):
                $urg = urgencyLevel((int)$st['days_enrolled']);
                $days = (int)$st['days_enrolled'];
                $days_class = $days >= 30 ? 'days-critical' : ($days >= 14 ? 'days-high' : 'days-normal');
                $pb_pct = min(100, round($days / 60 * 100));
                $pb_col = $days >= 30 ? 'pb-red' : ($days >= 14 ? 'pb-orange' : ($days >= 7 ? 'pb-yellow' : 'pb-green'));
            ?>
            <tr>
                <td><input type="checkbox" class="row-check row-selector" data-id="<?= $st['student_id'] ?>"></td>
                <td><?= htmlspecialchars($st['full_name']) ?></td>
                <td><?= htmlspecialchars($st['matric_no']) ?></td>
                <td><?= htmlspecialchars($st['email']) ?></td>
                <td>
                    <span class="<?= $days_class ?>"><?= $days ?> days</span>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar <?= $pb_col ?>" style="width:<?= $pb_pct ?>%"></div>
                    </div>
                </td>
                <td><span class="badge <?= $urg['class'] ?>"><?= $urg['label'] ?></span></td>
                <td>
                    <?php if ($st['reminder_sent']): ?>
                        <span class="badge badge-sent">✅ Sent</span>
                    <?php else: ?>
                        <span class="badge badge-not-sent">Not sent</span>
                    <?php endif; ?>
                </td>
                <td style="font-size:12px; color:var(--text-muted);">
                    <?= $st['last_reminder_date'] ? date('d M Y', strtotime($st['last_reminder_date'])) : '—' ?>
                </td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="remind_one">
                        <input type="hidden" name="student_id" value="<?= $st['student_id'] ?>">
                        <button type="submit"
                                class="btn-sm <?= $st['reminder_sent'] ? 'reminded' : 'remind' ?>"
                                <?= $st['reminder_sent'] ? 'title="Already reminded — send again?"' : '' ?>>
                            <?= $st['reminder_sent'] ? '📧 Re-send' : '📧 Remind' ?>
                        </button>
                    </form>
                    <a href="evaluation_form.php?student_id=<?= $st['student_id'] ?>">
                        <button class="btn-sm" style="margin-left:4px;">Evaluate</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <!-- Empty state -->
    <div class="empty-state">
        <div class="empty-icon">🎉</div>
        <h3>No flagged students</h3>
        <p>All students under your supervision have submitted at least one application.</p>
        <a href="student_monitoring.php" style="color:var(--gold); font-size:14px; margin-top:12px; display:inline-block;">
            ← Return to Monitoring Dashboard
        </a>
    </div>
    <?php endif; ?>

</div>

<script>
// Live search filter
document.getElementById('searchInput')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#flagTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
});

// Select all checkboxes
document.getElementById('checkAll')?.addEventListener('change', function () {
    document.querySelectorAll('.row-selector').forEach(cb => cb.checked = this.checked);
});

// CSV Export
function exportTable() {
    const rows = document.querySelectorAll('#flagTable tr');
    const csv  = Array.from(rows).map(row =>
        Array.from(row.querySelectorAll('th, td'))
            .slice(1, -1) // skip checkbox and action columns
            .map(cell => '"' + cell.textContent.trim().replace(/"/g, '""') + '"')
            .join(',')
    ).join('\n');

    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'zero_application_students_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
    URL.revokeObjectURL(url);
}
</script>
</body>
</html>
