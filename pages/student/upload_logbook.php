<?php
// pages/upload_logbook.php
/*
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Access Control Gate: Ensure only logged-in Students can access this upload interface
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Current authenticated student's user ID

try {
    // Fetch student's profile details to display on the workspace banner
    $student_stmt = $conn->prepare("SELECT full_name, matric_number, course FROM student WHERE user_id = ? LIMIT 1");
    $student_stmt->bind_param("i", $user_id);
    $student_stmt->execute();
    $student = $student_stmt->get_result()->fetch_assoc();

    // Mock/Fetch previous submissions history for this specific student
    // In a live system, you would query a 'logbook' table linked to student_id or user_id
    // $logbook_query = "SELECT * FROM logbook WHERE user_id = ? ORDER BY week_number DESC";
} catch (Exception $e) {
    error_log("Logbook Page Error: " . $e->getMessage());
} */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Logbook</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href="/MYIntern/css/style.css" rel="stylesheet">
    <script src="/MYIntern/js/script.js"></script>
</head>

<body>

    <main class="workspace-container">

        <div class="welcome-banner">
            <h1>Internship Logbook Submission</h1>
            <p>Course: <?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?> | Matric Number: <?php echo htmlspecialchars($student['matric_number'] ?? 'N/A'); ?></p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="background-color: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                🎉 Logbook document file submitted successfully for lecturer validation review.
            </div>
        <?php endif; ?>

        <div class="layout-grid">

            <section class="card-panel">
                <h2 class="card-title"><i class='bx bx-cloud-upload'></i> Submit New Logbook Entry</h2>

                <form action="../actions/process_logbook.php" method="POST" enctype="multipart/form-data" id="logbookForm">

                    <div class="form-group">
                        <p>Training Week No.</p>
                        <h1>1</h1>
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

                    <button type="submit" name="submit_logbook" class="submit-btn">Upload Weekly Logbook</button>
                    <button type="submit" name="submit_logbook" class="submit-btn">Back</button>
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
                }, false);
            });
        });
    </script>
</body>

</html>