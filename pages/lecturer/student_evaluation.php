<?php
// 1. Error Reporting Configuration (Helpful for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Fetch parameter from dashboard URL parameter tracking link
$student_id = $_GET['student_id'] ?? '';

if (empty($student_id)) {
    die("<div style='color:red; padding:20px; font-weight:bold;'>Error: Student ID parameter is completely missing.</div>");
}

$student_id = mysqli_real_escape_string($conn, $student_id);

// 3. Dynamic Database Fetch: Pull student metadata and company records
$query = "
    SELECT 
        s.matric_number, 
        s.full_name, 
        s.course,
        p.placement_id,
        c.company_name
    FROM student s
    INNER JOIN job_application ja ON s.matric_number = ja.matric_number
    INNER JOIN placement p ON ja.application_id = p.application_id
    INNER JOIN job_vacancy jv ON ja.job_id = jv.job_id
    INNER JOIN company c ON jv.company_id = c.company_id
    WHERE s.matric_number = '$student_id' AND p.status = 'Ongoing'
    LIMIT 1
";

$result = $conn->query($query);

if (!$result || $result->num_rows == 0) {
    die("<div style='color:red; padding:20px; font-weight:bold;'>Error: No active ongoing placement record found for Student ID: $student_id</div>");
}

$data = $result->fetch_assoc();
$placement_id = $data['placement_id'];

// 4. Form Upload Engine: Capture form inputs and save files directly as BLOB data
$msg_status = "";
$msg_text = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['evaluation_document'])) {
    $file = $_FILES['evaluation_document'];
    
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        $msg_status = "error";
        $msg_text = "Please select an appraisal document before clicking submit.";
    } else {
        $filename = $file['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed_extensions = ['pdf', 'doc', 'docx', 'zip'];
        
        if (!in_array(strtolower($filetype), $allowed_extensions)) {
            $msg_status = "error";
            $msg_text = "Invalid type! System restrictions only accept PDF, DOC, DOCX, or ZIP formats.";
        } else {
            // Convert file content into a binary data block stream
            $binary_file_data = file_get_contents($file['tmp_name']);

                $save_sql = "UPDATE evaluation SET evaluation_file = ?, uploaded_at = NOW() WHERE matric_number = ?";
                $stmt = $conn->prepare($save_sql);
                $null = null;
                $stmt->bind_param("bi", $null, $placement_id);
                $stmt->send_long_data(0, $binary_file_data);

            if ($stmt->execute()) {
                $msg_status = "success";
                $msg_text = "Assessment document uploaded and locked into the system successfully!";
            } else {
                $msg_status = "error";
                $msg_text = "Database storage malfunction: Failed to save raw file blob.";
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
    <link rel="stylesheet" href="../../css/unistyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>MYIntern | Review Logbook</title>
</head>

<body class="center-center">
<section class="evaluation-upload-section">
    
    <div class="evaluation-student-header">
        <div class="meta-profile-block">
            <div class="avatar-circle">
                <i class='bx bx-id-card'></i>
            </div>
            <div>
                <h2 class="intern-display-name"><?php echo htmlspecialchars($data['full_name']); ?></h2>
                <p class="intern-subtext">Matric: <strong><?php echo htmlspecialchars($data['matric_number']); ?></strong> &bull; <?php echo htmlspecialchars($data['course']); ?></p>
            </div>
        </div>
        <div class="company-badge-frame">
            <span class="badge-label">Assigned Placement</span>
            <span class="company-title"><?php echo htmlspecialchars($data['company_name']); ?></span>
        </div>
    </div>

    <?php if ($msg_status === 'success'): ?>
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 500;">
            <i class='bx bx-badge-check'></i> <?php echo $msg_text; ?>
        </div>
    <?php elseif ($msg_status === 'error'): ?>
        <div style="background-color: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 500;">
            <i class='bx bx-error-alt'></i> <?php echo $msg_text; ?>
        </div>
    <?php endif; ?>

    <div class="evaluation-grid-layout">
        
        <div class="instruction-panel-card">
            <h3>Evaluation Instructions</h3>
            <p class="instruction-text">
                First, evaluate the student logbook.
                Then, upload the signed evaluation document whether in PDF, Word or ZIP Format.
                Below is the example:
            </p>
            
            <div class="download-template-box">
                <div class="file-icon-box">
                    <i class='bx bxs-file-doc'></i>
                </div>
                <div class="file-details">
                    <span class="file-title-text">Internship_Evaluation_Form_v2026.docx</span>
                    <span class="file-size-text">Word Document &bull; 142 KB</span>
                </div>
                <a href="templates/Internship_Evaluation_Form_v2026.docx" class="template-download-link" download>
                    <i class='bx bx-download'></i>
                </a>
            </div>
        </div>

        <div class="upload-portal-card">
            <h3>Upload Completed Assessment</h3>
            <p class="instruction-text">Upload the finalized evaluation document. Only PDF, DOCX, or ZIP formats are allowed.</p>
            
            <form action="" method="POST" enctype="multipart/form-data" class="secure-upload-form">
                <input type="hidden" name="student_matric" value="<?php echo htmlspecialchars($data['matric_number']); ?>">
                
                <div class="drag-drop-interactive-zone" id="dropZoneContainer" style="cursor: pointer;" onclick="document.getElementById('hiddenFileInput').click();">
                    <input type="file" name="evaluation_document" id="hiddenFileInput" accept=".pdf,.doc,.docx,.zip" required style="display: none;">
                    <div class="upload-prompt-visual">
                        <i class='bx bx-cloud-upload upload-cloud-icon'></i>
                        <p class="prompt-main-message">Drag and drop file here or <span class="browse-text-highlight">browse files</span></p>
                        <p class="file-selection-preview-text" id="fileFeedbackText">No file selected</p>
                    </div>
                </div>

                <div class="form-action-row">
                    <button type="submit" class="submit-evaluation-btn">
                        <i class='bx bx-check-shield'></i> Submit Final Evaluation
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

<script>
    const fileInput = document.getElementById('hiddenFileInput');
    const feedbackText = document.getElementById('fileFeedbackText');
    const dropZone = document.getElementById('dropZoneContainer');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            feedbackText.textContent = `Selected: ${this.files[0].name}`;
            feedbackText.style.color = '#00A3C4';
            feedbackText.style.fontWeight = '600';
            dropZone.style.borderColor = '#00A3C4';
            dropZone.style.backgroundColor = '#F0FBFC';
        } else {
            feedbackText.textContent = "No file selected";
            feedbackText.style.color = '';
            feedbackText.style.fontWeight = '';
            dropZone.style.borderColor = '';
            dropZone.style.backgroundColor = '';
        }
    });
</script>
</body>
</html>