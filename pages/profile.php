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
    <script src="../js/script.js"></script>
    <title>MYIntern | About Us</title>
</head>

<body class="center">
    <?php include("../includes/header_user.php") ?>

    <div class="blue-container" id="about-container">
        <div class="square-profile-container">
            <img src="../assets/default-user.svg" alt="profile-pic" width=96 height=96 />
        </div>
    </div>

    <main class="main-area">
        <form class="two-column-grid-form">
            <div class="input-field-block">
                <label for="student_name">Name</label>
                <input type="text" id="student_name" value="TAM KAI DIT" readonly>
            </div>

            <div class="input-field-block">
                <label for="student_email">Email</label>
                <input type="email" id="student_email" value="d032410113@student.utem.edu.my" readonly>
            </div>

            <div class="input-field-block">
                <label for="student_phone">Phone Number</label>
                <input type="text" id="student_phone" value="011-1234 5678">
            </div>

            <div class="input-field-block">
                <label for="student_ic">Identification No.</label>
                <input type="text" id="student_ic" value="012345-05-0123">
            </div>

            <div class="input-field-block">
                <label for="student_matric_no">Matric Number</label>
                <input type="text" id="student_matric_no" value="D032410113">
            </div>

            <div class="input-field-block">
                <label for="student_course">Course</label>
                <input type="text" id="student_course" value="Diploma Computer Science" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Your Resume</label>
                <div class="upload-dropzone" id="dropzone">
                    <i class='bx bxs-file-blank'></i>
                    <p>Drag & Drop your file here or <strong>Browse files</strong></p>
                    <span style="font-size: 0.75rem; color: var(--text-muted); display:block; margin-top:4px;">Max file size: 5MB (PDF, DOCX)</span>

                    <input type="file" name="logbook_file" class="hidden-file-input" id="fileInput" accept=".pdf,.doc,.docx" required>
                </div>
                <div class="file-selected-name" id="fileSelectedName">
                    <i class='bx bx-check-double'></i> Selected File: <span id="fileNameSpan"></span>
                </div>
            </div>

            <div class="form-actions-row-centered">
                <button type="submit" class="teal-action-btn">Edit</button>
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

            // Event listener checking when manual file selection triggers change changes properties
            fileInput.addEventListener("change", function() {
                if (this.files.length > 0) {
                    fileNameSpan.textContent = this.files[0].name;
                    fileSelectedName.style.display = "block";
                }
            });

            // Handle UI drag and drop visual highlights matching state mutations
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