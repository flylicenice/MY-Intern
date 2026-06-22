<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php");

$user_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 1;
$week = $_GET['week'] ?? 1;
$student = ['full_name' => 'Student', 'matric_number' => 'N/A', 'course' => 'N/A'];
$message = "";

if (isset($conn)) {
    try {
        $student_stmt = $conn->prepare("SELECT full_name, matric_number, course FROM student WHERE user_id = ? LIMIT 1");
        $student_stmt->bind_param("i", $user_id);
        $student_stmt->execute();
        $res = $student_stmt->get_result()->fetch_assoc();
        if ($res) {
            $student = $res;
        }
    } catch (Exception $e) {
        error_log("Database banner load error: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logbook_file'])) {
        header('Content-Type: application/json');
        $placement_query = "SELECT p.placement_id 
                            FROM placement p
                            JOIN job_application ja ON p.application_id = ja.application_id
                            JOIN student s ON ja.matric_number = s.matric_number
                            WHERE s.user_id = ? LIMIT 1";
        $placement_stmt = $conn->prepare($placement_query);
        $placement_stmt->bind_param("i", $user_id);
        $placement_stmt->execute();
        $placement_res = $placement_stmt->get_result()->fetch_assoc();

        if (!$placement_res) {
            $real_placement_id = 1;
            $message = "<div style='background-color: #FEF08A; color: #854D0E; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;'>⚠️ Error: You cannot upload a logbook because you don't have an active internship record in the 'placement' table yet. Please insert a placeholder row in phpMyAdmin first!</div>";
        } else {
            $real_placement_id = $placement_res['placement_id'];
        }
        $file = $_FILES['logbook_file'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];


        if (!in_array($file_ext, $allowed_extensions)) {
            $message = "<div style='background-color: #FDE8E8; color: #9B1C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;'>❌ Error: Only PDF, DOC, and DOCX files are allowed.</div>";
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $message = "<div style='background-color: #FDE8E8; color: #9B1C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;'>❌ Error: File size exceeds the maximum 5MB limit.</div>";
        } else {

            $new_filename = "placement_" . $user_id . "_week_" . $week . "_" . time() . "." . $file_ext;


            $upload_dir = dirname(__DIR__, 2) . "/uploads/logbooks/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $upload_destination = $upload_dir . $new_filename;

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $upload_destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_destination)) {

                $action_query = "INSERT INTO logbook (week_number, logbook, placement_id, submitted_at) 
                                 VALUES (?, ?, ?, NOW())
                                 ON DUPLICATE KEY UPDATE logbook = VALUES(logbook), submitted_at = NOW()";

                $insert_stmt = $conn->prepare($action_query);
                $insert_stmt->bind_param("isi", $week, $new_filename, $real_placement_id);

                if ($insert_stmt->execute()) {

                    header("Location: student_dashboard.php?page=e-log&status=success");
                    exit();
                } else {

                    $message = "<div style='background-color: #FDE8E8; color: #9B1C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;'>❌ Database error processing your upload item.</div>";
                }
            } else {
                $message = "<div style='background-color: #FDE8E8; color: #9B1C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;'>❌ Failed to save uploaded file down to directory folder location.</div>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Logbook</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href="/MYIntern/css/style.css" rel="stylesheet">
    <link href="../../js/student.js"></script>
</head>

<body>
    <main class="workspace-container">

        <div class="welcome-banner">
            <h1>Internship Logbook Submission</h1>
            <p>Course: <?php echo htmlspecialchars($student['course']); ?> | Matric Number: <?php echo htmlspecialchars($student['matric_number']); ?></p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="background-color: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                🎉 Logbook document file submitted successfully for lecturer validation review.
            </div>
        <?php endif; ?>

        <div class="layout-grid">
            <section class="card-panel">
                <h2 class="card-title"><i class='bx bx-cloud-upload'></i> Submit New Logbook Entry</h2>

                <form action="" method="POST" enctype="multipart/form-data" id="logbookForm">
                    <div class="form-group">
                        <p>Training Week No.</p>
                        <h1><?php echo htmlspecialchars($week); ?></h1>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attach Weekly Document (PDF / Word Format)</label>
                        <div class="upload-dropzone" id="dropzone">
                            <p>Drag & Drop your file here or <strong>Browse files</strong></p>
                            <span style="font-size: 0.75rem; color: var(--text-muted); display:block; margin-top:4px;">Max file size: 5MB (PDF, DOCX)</span>

                            <input type="file" name="logbook_file" class="hidden-file-input" id="fileInput" accept=".pdf,.doc,.docx" required>
                        </div>
                        <div class="file-selected-name" id="fileSelectedName">
                            <i class='bx bx-check-double'></i> Selected File: <span id="fileNameSpan"></span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <a href="student_dashboard.php?page=e-log" style="text-decoration: none; padding: 12px 24px; border: 1px solid #cbd5e1; color: #475569; border-radius: 6px; font-weight: 600; font-size: 0.9rem;">Back</a>
                        <button type="submit" class="submit-btn" style="background: #2dd4bf; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">Upload Weekly Logbook</button>
                    </div>
                </form>
            </section>

        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("fileInput");
            const dropzone = document.getElementById("dropzone");
            const fileSelectedName = document.getElementById("fileSelectedName");
            const fileNameSpan = document.getElementById("fileNameSpan");

            dropzone.addEventListener("click", function(e) {
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });
            fileInput.addEventListener("change", function() {
                if (this.files.length > 0) {
                    fileNameSpan.textContent = this.files[0].name;
                    fileSelectedName.style.display = "block";
                }
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');

                    if (e.type === 'drop' && e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        fileNameSpan.textContent = e.dataTransfer.files[0].name;
                        fileSelectedName.style.display = "block";
                    }
                }, false);
            });
        });
    </script>
</body>

</html>