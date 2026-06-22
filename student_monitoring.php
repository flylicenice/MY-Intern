<?php
// student_monitoring.php
// Requires: session_start(), DB connection ($conn), and lecturer auth check before including
// Place in: pages/lecturer/student_monitoring.php

session_start();
include '../../pages/lecturer_dashboard_header.php';
include '../../includes/db.php'; // adjust path as needed

// Fetch all students supervised by this lecturer
$lecturer_id = $_SESSION['lecturer_id'];

$sql = "
    SELECT 
        s.student_id,
        s.full_name,
        s.matric_no,
        s.phone_number,
        u.email,
        COALESCE(app_count.total_applications, 0) AS total_applications,
        COALESCE(int_count.total_interviews, 0) AS total_interviews,
        COALESCE(placed.company_name, 'None') AS placed_company,
        CASE 
            WHEN placed.company_name IS NOT NULL THEN 'Hired'
            WHEN int_count.total_interviews > 0 THEN 'Interviewing'
            WHEN app_count.total_applications = 0 THEN 'Zero Applications'
            WHEN app_count.total_applications > 0 THEN 'Seeking'
            ELSE 'Unknown'
        END AS status
    FROM student s
    JOIN user u ON s.user_id = u.user_id
    LEFT JOIN (
        SELECT student_id, COUNT(*) AS total_applications
        FROM application
        GROUP BY student_id
    ) app_count ON s.student_id = app_count.student_id
    LEFT JOIN (
        SELECT a.student_id, COUNT(*) AS total_interviews
        FROM interview i
        JOIN application a ON i.application_id = a.application_id
        GROUP BY a.student_id
    ) int_count ON s.student_id = int_count.student_id
    LEFT JOIN (
        SELECT a.student_id, c.company_name
        FROM application a
        JOIN company c ON a.company_id = c.company_id
        WHERE a.status = 'accepted'
        LIMIT 1
    ) placed ON s.student_id = placed.student_id
    WHERE s.lecturer_id = ?
    ORDER BY 
        CASE WHEN app_count.total_applications = 0 THEN 0 ELSE 1 END ASC,
        s.full_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Summary counts
$total = count($students);
$hired = 0; $interviewing = 0; $zero_app = 0; $seeking = 0;
foreach ($students as $s) {
    if ($s['status'] === 'Hired') $hired++;
    elseif ($s['status'] === 'Interviewing') $interviewing++;
    elseif ($s['status'] === 'Zero Applications') $zero_app++;
    else $seeking++;
}
$placement_rate = $total > 0 ? round(($hired / $total) * 100) : 0;

// Handle search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Monitoring | MyIntern</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        /* ===== MyIntern colour tokens ===== */
        :root {
            --gold:        #C8A96E;
            --gold-dark:   #A8893E;
            --gold-light:  #E8D9B5;
            --gold-bg:     #F5EDD8;
            --dark-header: #3D3D2E;
            --text-main:   #1a1a1a;
            --text-muted:  #666;
            --bg-page:     #f7f6f2;
            --bg-white:    #ffffff;
            --border:      #e0d9c8;
            --radius:      6px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg-page); color: var(--text-main); }

        /* ── Page wrapper ── */
        .page-wrap { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

        /* ── Page title ── */
        .page-title { font-size: 22px; font-weight: 600; color: var(--dark-header); margin-bottom: 20px; }

        /* ── Stat cards row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--gold-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .stat-icon.red   { background: #fde8e8; }
        .stat-icon.green { background: #e8f5e9; }
        .stat-icon.blue  { background: #e3f0fb; }
        .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 22px; font-weight: 700; color: var(--dark-header); line-height: 1.1; }

        /* ── Alert banner for zero-app students ── */
        .alert-banner {
            background: #fff3cd;
            border: 1px solid #f0c040;
            border-left: 4px solid #f0c040;
            border-radius: var(--radius);
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #7a5800;
        }
        .alert-banner.hidden { display: none; }
        .alert-icon { font-size: 20px; }

        /* ── Toolbar ── */
        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            align-items: center;
        }
        .toolbar input[type="text"] {
            flex: 1; min-width: 200px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            outline: none;
        }
        .toolbar input:focus { border-color: var(--gold); }
        .toolbar select {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            background: var(--bg-white);
            cursor: pointer;
        }
        .btn {
            padding: 8px 16px;
            border-radius: var(--radius);
            font-size: 14px;
            cursor: pointer;
            border: none;
            font-weight: 500;
        }
        .btn-gold { background: var(--gold); color: #fff; }
        .btn-gold:hover { background: var(--gold-dark); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-main); }
        .btn-outline:hover { background: var(--gold-bg); }

        /* ── Table ── */
        .table-wrap { background: var(--bg-white); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead tr { background: var(--dark-header); }
        thead th {
            padding: 12px 16px;
            color: #fff;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--gold-bg); }
        tbody tr.flag-row { background: #fff8e1; }
        tbody tr.flag-row:hover { background: #fff0b3; }
        td { padding: 11px 16px; vertical-align: middle; }

        /* ── Status badges ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-hired       { background: #d4edda; color: #155724; }
        .badge-interviewing{ background: #cce5ff; color: #004085; }
        .badge-seeking     { background: #fff3cd; color: #856404; }
        .badge-zero        { background: #f8d7da; color: #721c24; }
        .badge-placed      { background: #d1ecf1; color: #0c5460; }

        /* ── Flag icon for zero-app ── */
        .flag-icon { color: #dc3545; font-size: 16px; margin-right: 4px; }

        /* ── Action buttons in table ── */
        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 4px;
            border: 1px solid var(--border);
            background: var(--bg-white);
            cursor: pointer;
            color: var(--text-main);
        }
        .btn-sm:hover { background: var(--gold-bg); border-color: var(--gold); }
        .btn-sm.danger { border-color: #dc3545; color: #dc3545; }
        .btn-sm.danger:hover { background: #fde8e8; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .empty-state p { margin-top: 8px; font-size: 14px; }

        /* ── Pagination ── */
        .pagination { display: flex; justify-content: flex-end; gap: 6px; margin-top: 16px; }
        .pagination button {
            padding: 6px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--bg-white);
            cursor: pointer;
            font-size: 13px;
        }
        .pagination button.active { background: var(--gold); color: #fff; border-color: var(--gold); }
        .pagination button:hover:not(.active) { background: var(--gold-bg); }

        /* ── Modal overlay ── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--bg-white);
            border-radius: 8px;
            padding: 28px;
            width: 420px;
            max-width: 95vw;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
        }
        .modal-title { font-size: 17px; font-weight: 600; margin-bottom: 16px; color: var(--dark-header); }
        .modal-body { font-size: 14px; color: var(--text-main); line-height: 1.6; }
        .modal-body p { margin-bottom: 8px; }
        .modal-body strong { color: var(--dark-header); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px; }
        .modal-close-btn { cursor: pointer; float: right; font-size: 20px; color: var(--text-muted); background: none; border: none; }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="page-title">Student Monitoring Dashboard</div>

    <!-- ── Summary stats ── -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div>
                <div class="stat-label">Placement Rate</div>
                <div class="stat-value"><?= $placement_rate ?>%</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">✅</div>
            <div>
                <div class="stat-label">Hired</div>
                <div class="stat-value"><?= $hired ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">🤝</div>
            <div>
                <div class="stat-label">Interviewing</div>
                <div class="stat-value"><?= $interviewing ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔍</div>
            <div>
                <div class="stat-label">Seeking</div>
                <div class="stat-value"><?= $seeking ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">⚠️</div>
            <div>
                <div class="stat-label">Zero Applications</div>
                <div class="stat-value"><?= $zero_app ?></div>
            </div>
        </div>
    </div>

    <!-- ── Zero-application alert ── -->
    <div class="alert-banner <?= $zero_app === 0 ? 'hidden' : '' ?>">
        <span class="alert-icon">⚠️</span>
        <div>
            <strong><?= $zero_app ?> student(s) have not submitted any applications yet.</strong>
            These students are highlighted in red below. Consider reaching out to them.
        </div>
    </div>

    <!-- ── Toolbar ── -->
    <form method="GET" action="">
        <div class="toolbar">
            <input type="text" name="search" placeholder="Search by name or matric no..."
                   value="<?= htmlspecialchars($search) ?>">
            <select name="status">
                <option value="all"  <?= $status_filter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                <option value="Hired"             <?= $status_filter === 'Hired' ? 'selected' : '' ?>>Hired</option>
                <option value="Placed"            <?= $status_filter === 'Placed' ? 'selected' : '' ?>>Placed</option>
                <option value="Interviewing"      <?= $status_filter === 'Interviewing' ? 'selected' : '' ?>>Interviewing</option>
                <option value="Seeking"           <?= $status_filter === 'Seeking' ? 'selected' : '' ?>>Seeking</option>
                <option value="Zero Applications" <?= $status_filter === 'Zero Applications' ? 'selected' : '' ?>>Zero Applications</option>
            </select>
            <button class="btn btn-gold" type="submit">Filter</button>
            <a href="student_monitoring.php"><button class="btn btn-outline" type="button">Reset</button></a>
        </div>
    </form>

    <!-- ── Students table ── -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Matric No</th>
                    <th>Applications</th>
                    <th>Interviews</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
            <?php
            $any_shown = false;
            foreach ($students as $st):
                // Apply filters
                if (!empty($search)) {
                    $q = strtolower($search);
                    if (strpos(strtolower($st['full_name']), $q) === false &&
                        strpos(strtolower($st['matric_no']), $q) === false) continue;
                }
                if ($status_filter !== 'all' && $st['status'] !== $status_filter) continue;

                $is_zero = $st['status'] === 'Zero Applications';
                $any_shown = true;

                $badge_class = match($st['status']) {
                    'Hired'             => 'badge-hired',
                    'Placed'            => 'badge-placed',
                    'Interviewing'      => 'badge-interviewing',
                    'Seeking'           => 'badge-seeking',
                    'Zero Applications' => 'badge-zero',
                    default             => 'badge-seeking',
                };
            ?>
            <tr class="<?= $is_zero ? 'flag-row' : '' ?>">
                <td>
                    <?php if ($is_zero): ?>
                        <span class="flag-icon">⚑</span>
                    <?php endif; ?>
                    <?= htmlspecialchars($st['full_name']) ?>
                </td>
                <td><?= htmlspecialchars($st['matric_no']) ?></td>
                <td><?= (int)$st['total_applications'] ?></td>
                <td><?= (int)$st['total_interviews'] ?></td>
                <td><?= htmlspecialchars($st['placed_company']) ?></td>
                <td><span class="badge <?= $badge_class ?>"><?= $st['status'] ?></span></td>
                <td>
                    <button class="btn-sm"
                        onclick="openDetail(
                            '<?= htmlspecialchars($st['full_name'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($st['matric_no'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($st['email'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($st['phone_number'], ENT_QUOTES) ?>',
                            <?= (int)$st['total_applications'] ?>,
                            <?= (int)$st['total_interviews'] ?>,
                            '<?= $st['status'] ?>',
                            '<?= htmlspecialchars($st['placed_company'], ENT_QUOTES) ?>'
                        )">
                        View Details
                    </button>
                    <?php if ($is_zero): ?>
                    <button class="btn-sm danger" style="margin-left:4px;"
                        onclick="sendReminder(<?= $st['student_id'] ?>, '<?= htmlspecialchars($st['full_name'], ENT_QUOTES) ?>')">
                        Send Reminder
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$any_shown): ?>
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div style="font-size:32px;">🔍</div>
                        <p>No students match your search or filter.</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ── Student Detail Modal ── -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-box">
        <button class="modal-close-btn" onclick="closeModal('detailModal')">✕</button>
        <div class="modal-title" id="modalStudentName">Student Detail</div>
        <div class="modal-body" id="modalContent"></div>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="closeModal('detailModal')">Close</button>
            <a id="modalViewProfile" href="#"><button class="btn btn-gold">View Full Profile</button></a>
        </div>
    </div>
</div>

<!-- ── Reminder Confirm Modal ── -->
<div class="modal-overlay" id="reminderModal">
    <div class="modal-box">
        <div class="modal-title">Send Reminder</div>
        <div class="modal-body" id="reminderContent"></div>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="closeModal('reminderModal')">Cancel</button>
            <button class="btn btn-gold" id="confirmReminderBtn">Send</button>
        </div>
    </div>
</div>

<script>
function openDetail(name, matric, email, phone, apps, interviews, status, company) {
    document.getElementById('modalStudentName').textContent = name;
    document.getElementById('modalContent').innerHTML = `
        <p><strong>Matric No:</strong> ${matric}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Phone:</strong> ${phone}</p>
        <p><strong>Applications Submitted:</strong> ${apps}</p>
        <p><strong>Interviews Attended:</strong> ${interviews}</p>
        <p><strong>Current Status:</strong> ${status}</p>
        <p><strong>Placed At:</strong> ${company}</p>
    `;
    document.getElementById('detailModal').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

let pendingReminderId = null;
function sendReminder(studentId, name) {
    pendingReminderId = studentId;
    document.getElementById('reminderContent').innerHTML =
        `Send an application reminder email to <strong>${name}</strong>?<br><br>
        This will notify them that they have not submitted any internship applications.`;
    document.getElementById('reminderModal').classList.add('open');
}

document.getElementById('confirmReminderBtn').addEventListener('click', function () {
    if (!pendingReminderId) return;
    // AJAX call to a reminder endpoint
    fetch('../../actions/send_reminder.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ student_id: pendingReminderId })
    })
    .then(r => r.json())
    .then(data => {
        closeModal('reminderModal');
        alert(data.success ? 'Reminder sent successfully!' : 'Failed to send reminder.');
    })
    .catch(() => {
        closeModal('reminderModal');
        alert('Error sending reminder. Please try again.');
    });
});

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});
</script>
</body>
</html>
