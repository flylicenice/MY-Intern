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
<!-- Main Context Section Container -->
<section class="evaluation-upload-section">
    
    <!-- 1. Student Identity Context Frame -->
    <div class="evaluation-student-header">
        <div class="meta-profile-block">
            <div class="avatar-circle">
                <i class='bx bx-id-card'></i>
            </div>
            <div>
                <h2 class="intern-display-name">TAM KAI DIT</h2>
                <p class="intern-subtext">Matric: <strong>D032410113</strong> &bull; Diploma Computer Science</p>
            </div>
        </div>
        <div class="company-badge-frame">
            <span class="badge-label">Assigned Placement</span>
            <span class="company-title">Google Sdn. Bhd.</span>
        </div>
    </div>

    <!-- 2. Dual-Column Evaluation Hub -->
    <div class="evaluation-grid-layout">
        
        <!-- Left Side Column: Instructions & Template Downloads -->
        <div class="instruction-panel-card">
            <h3>Evaluation Instructions</h3>
            <p class="instruction-text">
                First, evaluate the student logbook.
                Then, upload the signed evaluation document whether in PDF, Word or ZIP Format.
                Below is the example:
            
            <div class="download-template-box">
                <div class="file-icon-box">
                    <i class='bx bxs-file-doc'></i>
                </div>
                <div class="file-details">
                    <span class="file-title-text">Internship_Evaluation_Form_v2026.docx</span>
                    <span class="file-size-text">Word Document &bull; 142 KB</span>
                </div>
                <!-- Template download link -->
                <a href="templates/Internship_Evaluation_Form_v2026.docx" class="template-download-link" download>
                    <i class='bx bx-download'></i>
                </a>
            </div>
        </div>

        <!-- Right Side Column: File Upload Processing Form -->
        <div class="upload-portal-card">
            <h3>Upload Completed Assessment</h3>
            <p class="instruction-text">Upload the finalized evaluation document. Only PDF, DOCX, or ZIP formats are allowed.</p>
            
            <!-- Standard backend submission processing script trigger -->
            <form action="process_evaluation_upload.php" method="POST" enctype="multipart/form-data" class="secure-upload-form">
                <!-- Hidden parameter input mapping to preserve specific student identity record -->
                <input type="hidden" name="student_matric" value="D032410113">
                
                <!-- Interactive Drag and Drop Container Zone -->
                <div class="drag-drop-interactive-zone" id="dropZoneContainer">
                    <input type="file" name="evaluation_document" id="hiddenFileInput" accept=".pdf,.doc,.docx,.zip" required>
                    <div class="upload-prompt-visual">
                        <i class='bx bx-cloud-upload upload-cloud-icon'></i>
                        <p class="prompt-main-message">Drag and drop file here or <span class="browse-text-highlight">browse files</span></p>
                        <p class="file-selection-preview-text" id="fileFeedbackText">No file selected</p>
                    </div>
                </div>

                <!-- Action Button Controls Block -->
                <div class="form-action-row">
                    <button type="submit" class="submit-evaluation-btn">
                        <i class='bx bx-check-shield'></i> Submit Final Evaluation
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

<!-- Lightweight Native JS Input Change Feedback Handler -->
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
        }
    });
</script>
</body>