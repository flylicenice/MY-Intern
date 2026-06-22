<?php
session_start();

if (!isset($_SESSION['matric_number'])) {
    header("Location: login.php");
    exit();
}
include("../includes/get_profile_data.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/student.js"></script>
    <title>MYIntern | About Us</title>
</head>

<body class="center">
    <?php include("../includes/header_user.php") ?>

    <div class="blue-container" id="about-container">
    </div>

    <main class="main-area">
        <form class="two-column-grid-form" id="studentProfileForm" enctype='multipart/form-data'>
            <div class="input-field-block">
                <label for="student_name">Name</label>
                <input type="text" name="student_name" id="student_name" value="<?php echo $name; ?>">
            </div>

            <div class="input-field-block">
                <label for="student_email">Email</label>
                <input type="email" name="student_email" id="student_email" value="<?php echo $email ?>" readonly>
            </div>

            <div class="input-field-block">
                <label for="student_phone">Phone Number</label>
                <input type="text" name="student_phone" id="student_phone" value="<?php echo $phoneNo; ?>">
            </div>

            <div class="input-field-block">
                <label for="student_ic">Identification No.</label>
                <input type="text" name="student_ic" id="student_ic" value="<?php echo $IC; ?>" readonly>
            </div>

            <div class="input-field-block">
                <label for="student_matric_no">Matric Number</label>
                <input type="text" name="student_matric_no" id="student_matric_no" value="<?php echo $matricNo; ?>" readonly>
            </div>

            <div class="input-field-block">
                <label for="student_course">Course</label>
                <input type="text" id="student_course" value="<?php echo $course; ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Your Resume</label>

                <?php if (isset($_SESSION['has_resume']) && $_SESSION['has_resume'] == 1): ?>
                    <div class="resume-status-badge" style="margin-bottom: 12px; padding: 12px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #166534; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                            <i class='bx bx-check-shield' style="font-size: 1.1rem;"></i> Resume Document Attached
                        </span>
                        <a href="../includes/view_resume.php" target="_blank" class="teal-action-btn" style="padding: 6px 14px; font-size: 0.8rem; text-decoration: none; border-radius: 4px;">
                            View Document
                        </a>
                    </div>
                <?php endif; ?>

                <div class="upload-dropzone" id="dropzone" onclick="document.getElementById('fileInput').click();" style="cursor: pointer;">
                    <i class='bx bxs-file-blank'></i>
                    <p>
                        <?php if (isset($_SESSION['has_resume']) && $_SESSION['has_resume'] == 1): ?>
                            Drag & Drop to <strong>Replace your file</strong> or Browse
                        <?php else: ?>
                            Drag & Drop your file here or <strong>Browse files</strong>
                        <?php endif; ?>
                    </p>
                    <span style="font-size: 0.75rem; color: var(--text-muted); display:block; margin-top:4px;">Max file size: 5MB (PDF, DOCX)</span>

                    <input type="file" name="resume_file" class="hidden-file-input" id="fileInput" accept=".pdf,.doc,.docx" style="display: none;"
                        <?php echo (!isset($_SESSION['has_resume']) || $_SESSION['has_resume'] == 0) ? 'required' : ''; ?>>
                </div>

                <div class="file-selected-name" id="fileSelectedName" style="display: none; margin-top: 8px;">
                    <span style="color: #111e4b; font-size: 0.85rem; font-weight: 500;">
                        <i class='bx bx-check-double'></i> Selected New File: <span id="fileNameSpan" style="font-weight: 600;"></span>
                    </span>
                </div>
            </div>

            <div class="input-field-block">
                <label for="student_course">Intern Status</label>
                <input type="text" id="student_course" value="<?php echo strtoupper($internStatus); ?>" readonly>
            </div>

            <div class="form-actions-row-centered">
                <button type="submit" class="teal-action-btn" id="updateProfileBtn">Update</button>
            </div>
        </form>
    </main>

    <?php include("../includes/footer.php"); ?>

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