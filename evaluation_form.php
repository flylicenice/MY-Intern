<?php
// evaluation_form.php
// Lecturer submits internship evaluation for a specific student
// Place in: pages/lecturer/evaluation_form.php

session_start();
include '../../includes/lecturer_dashboard_header.php';
include '../../config/db.php';

$lecturer_id = $_SESSION['lecturer_id'];
$student_id  = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
$success_msg = '';
$error_msg   = '';

// Fetch student info
$student = null;
if ($student_id) {
    $st_sql  = "SELECT s.full_name, s.matric_no, s.student_id, c.company_name, a.start_date, a.end_date
                FROM student s
                LEFT JOIN application a ON s.student_id = a.student_id AND a.status = 'accepted'
                LEFT JOIN company c ON a.company_id = c.company_id
                WHERE s.student_id = ? AND s.lecturer_id = ?
                LIMIT 1";
    $st_stmt = $conn->prepare($st_sql);
    $st_stmt->bind_param("ii", $student_id, $lecturer_id);
    $st_stmt->execute();
    $student = $st_stmt->get_result()->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid           = (int)$_POST['student_id'];
    $professionalism = (int)$_POST['professionalism'];
    $technical       = (int)$_POST['technical'];
    $communication   = (int)$_POST['communication'];
    $punctuality     = (int)$_POST['punctuality'];
    $initiative      = (int)$_POST['initiative'];
    $teamwork        = (int)$_POST['teamwork'];
    $overall_grade   = $_POST['overall_grade'];
    $comments        = $conn->real_escape_string($_POST['comments']);
    $recommend       = isset($_POST['recommend']) ? 1 : 0;
    $eval_date       = date('Y-m-d');

    // Upsert: update if already exists, insert otherwise
    $check_sql  = "SELECT evaluation_id FROM evaluation WHERE student_id = ? AND lecturer_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $sid, $lecturer_id);
    $check_stmt->execute();
    $existing = $check_stmt->get_result()->fetch_assoc();

    if ($existing) {
        $upd_sql = "UPDATE evaluation SET
                        professionalism=?, technical_skills=?, communication=?,
                        punctuality=?, initiative=?, teamwork=?,
                        overall_grade=?, comments=?, recommend=?, eval_date=?
                    WHERE evaluation_id=?";
        $upd_stmt = $conn->prepare($upd_sql);
        $upd_stmt->bind_param(
            "iiiiiiississi",
            $professionalism, $technical, $communication,
            $punctuality, $initiative, $teamwork,
            $overall_grade, $comments, $recommend, $eval_date,
            $existing['evaluation_id']
        );
        $upd_stmt->execute();
    } else {
        $ins_sql = "INSERT INTO evaluation
                        (student_id, lecturer_id, professionalism, technical_skills, communication,
                         punctuality, initiative, teamwork, overall_grade, comments, recommend, eval_date)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $ins_stmt = $conn->prepare($ins_sql);
        $ins_stmt->bind_param(
            "iiiiiiiisssi",
            $sid, $lecturer_id,
            $professionalism, $technical, $communication,
            $punctuality, $initiative, $teamwork,
            $overall_grade, $comments, $recommend, $eval_date
        );
        $ins_stmt->execute();
    }

    $success_msg = "Evaluation saved successfully!";
}

// Load existing evaluation if any
$existing_eval = null;
if ($student_id) {
    $ev_sql  = "SELECT * FROM evaluation WHERE student_id = ? AND lecturer_id = ?";
    $ev_stmt = $conn->prepare($ev_sql);
    $ev_stmt->bind_param("ii", $student_id, $lecturer_id);
    $ev_stmt->execute();
    $existing_eval = $ev_stmt->get_result()->fetch_assoc();
}

function v($key, $default = '', $existing = null) {
    if ($existing && isset($existing[$key])) return htmlspecialchars($existing[$key]);
    return $default;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Form | MyIntern</title>
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

        .page-wrap { max-width: 800px; margin: 0 auto; padding: 24px 20px; }
        .page-title { font-size: 22px; font-weight: 600; color: var(--dark-header); margin-bottom: 20px; }

        /* Student info card */
        .info-card {
            background: var(--gold-bg);
            border: 1px solid var(--gold-light);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex; gap: 20px; flex-wrap: wrap;
        }
        .info-item { font-size: 14px; }
        .info-label { color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: var(--dark-header); }

        /* Alert / success messages */
        .msg { padding: 12px 16px; border-radius: var(--radius); margin-bottom: 18px; font-size: 14px; }
        .msg-success { background: #d4edda; border: 1px solid #b8ddc8; color: #155724; }
        .msg-error   { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }

        /* Form card */
        .form-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .form-section-title {
            background: var(--dark-header);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            letter-spacing: .3px;
        }
        .form-body { padding: 20px; }

        /* Criteria rows */
        .criteria-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }
        .criteria-row:last-child { border-bottom: none; }
        .criteria-label { flex: 1; font-size: 14px; font-weight: 500; }
        .criteria-desc  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .star-group { display: flex; gap: 4px; direction: rtl; }
        .star-group input[type="radio"] { display: none; }
        .star-group label {
            font-size: 24px;
            cursor: pointer;
            color: #ddd;
            transition: color .15s;
        }
        .star-group input[type="radio"]:checked ~ label,
        .star-group label:hover,
        .star-group label:hover ~ label {
            color: var(--gold);
        }
        .score-display {
            width: 36px;
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-header);
            text-align: center;
        }

        /* Overall grade */
        .grade-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
        .grade-btn { display: none; }
        .grade-btn + label {
            padding: 8px 20px;
            border: 1px solid var(--border);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }
        .grade-btn[value="A"] + label { border-color: #28a745; color: #28a745; }
        .grade-btn[value="B"] + label { border-color: #17a2b8; color: #17a2b8; }
        .grade-btn[value="C"] + label { border-color: #ffc107; color: #856404; }
        .grade-btn[value="D"] + label { border-color: #fd7e14; color: #7a3a0b; }
        .grade-btn[value="F"] + label { border-color: #dc3545; color: #dc3545; }
        .grade-btn:checked + label { color: #fff !important; }
        .grade-btn[value="A"]:checked + label { background: #28a745; border-color: #28a745; }
        .grade-btn[value="B"]:checked + label { background: #17a2b8; border-color: #17a2b8; }
        .grade-btn[value="C"]:checked + label { background: #ffc107; border-color: #ffc107; }
        .grade-btn[value="D"]:checked + label { background: #fd7e14; border-color: #fd7e14; }
        .grade-btn[value="F"]:checked + label { background: #dc3545; border-color: #dc3545; }

        /* Textarea */
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            outline: none;
            margin-top: 10px;
        }
        textarea:focus { border-color: var(--gold); }

        /* Recommend checkbox */
        .recommend-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 0;
            border-top: 1px solid var(--border);
            margin-top: 14px;
            font-size: 14px;
        }
        .recommend-row input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--gold); cursor: pointer; }

        /* Submit button */
        .btn-submit {
            display: block;
            width: 100%;
            padding: 13px;
            background: var(--gold);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background .15s;
        }
        .btn-submit:hover { background: var(--gold-dark); }
        .btn-back { display: inline-block; margin-bottom: 16px; font-size: 14px; color: var(--gold); text-decoration: none; }
        .btn-back:hover { color: var(--gold-dark); text-decoration: underline; }

        /* No student warning */
        .no-student { text-align: center; padding: 40px; color: var(--text-muted); }
    </style>
</head>
<body>
<div class="page-wrap">
    <a class="btn-back" href="student_monitoring.php">← Back to Monitoring Dashboard</a>
    <div class="page-title">Internship Evaluation Form</div>

    <?php if (!$student): ?>
    <div class="no-student">
        <div style="font-size:40px;">⚠️</div>
        <p style="margin-top:10px; font-size:16px;">No student selected or student not assigned to you.</p>
        <a href="student_monitoring.php" style="color:var(--gold)">Return to dashboard</a>
    </div>
    <?php else: ?>

    <?php if ($success_msg): ?><div class="msg msg-success">✅ <?= $success_msg ?></div><?php endif; ?>
    <?php if ($error_msg):   ?><div class="msg msg-error">❌ <?= $error_msg ?></div><?php endif; ?>

    <!-- Student info banner -->
    <div class="info-card">
        <div class="info-item">
            <div class="info-label">Student Name</div>
            <div class="info-value"><?= htmlspecialchars($student['full_name']) ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Matric No</div>
            <div class="info-value"><?= htmlspecialchars($student['matric_no']) ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Company</div>
            <div class="info-value"><?= htmlspecialchars($student['company_name'] ?? 'Not Placed') ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Internship Period</div>
            <div class="info-value">
                <?= $student['start_date'] ? date('d M Y', strtotime($student['start_date'])) . ' – ' . date('d M Y', strtotime($student['end_date'])) : '—' ?>
            </div>
        </div>
    </div>

    <form method="POST" action="evaluation_form.php">
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">

        <!-- ── Performance Criteria ── -->
        <div class="form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Performance Criteria (1–5 Stars)</div>
            <div class="form-body">

                <?php
                $criteria = [
                    ['key' => 'professionalism', 'label' => 'Professionalism', 'desc' => 'Attitude, appearance, and conduct at the workplace'],
                    ['key' => 'technical',       'label' => 'Technical Skills', 'desc' => 'Application of academic knowledge and technical competency'],
                    ['key' => 'communication',   'label' => 'Communication',    'desc' => 'Ability to communicate clearly with supervisors and colleagues'],
                    ['key' => 'punctuality',     'label' => 'Punctuality',      'desc' => 'Attendance, timeliness, and meeting deadlines'],
                    ['key' => 'initiative',      'label' => 'Initiative',       'desc' => 'Proactive behaviour and willingness to take responsibility'],
                    ['key' => 'teamwork',        'label' => 'Teamwork',         'desc' => 'Collaboration and contribution within a team setting'],
                ];
                foreach ($criteria as $c):
                    $cur = $existing_eval[$c['key']] ?? $existing_eval['technical_skills'] ?? 3;
                    if ($c['key'] === 'technical') $cur = $existing_eval['technical_skills'] ?? 3;
                    else $cur = $existing_eval[$c['key']] ?? 3;
                ?>
                <div class="criteria-row">
                    <div class="criteria-label">
                        <?= $c['label'] ?>
                        <div class="criteria-desc"><?= $c['desc'] ?></div>
                    </div>
                    <div class="star-group" id="stars_<?= $c['key'] ?>">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="<?= $c['key'] ?>_<?= $i ?>"
                               name="<?= $c['key'] === 'technical' ? 'technical' : $c['key'] ?>"
                               value="<?= $i ?>"
                               <?= ($cur == $i) ? 'checked' : '' ?>>
                        <label for="<?= $c['key'] ?>_<?= $i ?>" title="<?= $i ?> star">★</label>
                        <?php endfor; ?>
                    </div>
                    <div class="score-display" id="score_<?= $c['key'] ?>"><?= $cur ?>/5</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ── Overall Grade ── -->
        <div class="form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Overall Grade</div>
            <div class="form-body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                    Select the overall internship grade for this student.
                </p>
                <div class="grade-row">
                    <?php foreach (['A','B','C','D','F'] as $g): ?>
                    <input class="grade-btn" type="radio" id="grade_<?= $g ?>" name="overall_grade" value="<?= $g ?>"
                           <?= (v('overall_grade','B',$existing_eval) === $g) ? 'checked' : '' ?>>
                    <label for="grade_<?= $g ?>"><?= $g ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ── Comments & Recommendation ── -->
        <div class="form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Comments & Recommendation</div>
            <div class="form-body">
                <label style="font-size:14px; font-weight:500;">Lecturer's Comments</label>
                <textarea name="comments" placeholder="Enter any remarks, strengths, areas for improvement, or overall impressions about the student's internship performance..."><?= v('comments','',$existing_eval) ?></textarea>

                <div class="recommend-row">
                    <input type="checkbox" id="recommend" name="recommend" value="1"
                           <?= ($existing_eval['recommend'] ?? 0) ? 'checked' : '' ?>>
                    <label for="recommend" style="cursor:pointer;">
                        I recommend this student for future internship opportunities or full-time employment.
                    </label>
                </div>
            </div>
        </div>

        <button class="btn-submit" type="submit">
            <?= $existing_eval ? '✏️ Update Evaluation' : '💾 Submit Evaluation' ?>
        </button>
    </form>
    <?php endif; ?>
</div>

<script>
// Live star score display
document.querySelectorAll('.star-group input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const key = this.name === 'technical' ? 'technical' : this.name;
        const display = document.getElementById('score_' + key);
        if (display) display.textContent = this.value + '/5';
    });
});
</script>
</body>
</html>
