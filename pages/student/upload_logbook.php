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
    <title>Upload Logbook - MYIntern</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --navy-blue: #111E4B;
            --gold-accent: #E2C279;
            --bg-canvas: #F8FAFC;
            --border-color: #E2E8F0;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --success-green: #10B981;
            --warning-amber: #F59E0B;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-dark);
        }

        /* Top Navigation Header bar matches main dashboard style */
        .top-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy-blue);
            text-decoration: none;
        }
        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        /* Main Workspace Container layout layout split grid */
        .workspace-container {
            max-width: 1200px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
            box-sizing: border-box;
        }

        .welcome-banner {
            margin-bottom: 2rem;
        }
        .welcome-banner h1 {
            margin: 0;
            font-size: 1.8rem;
            color: var(--navy-blue);
        }
        .welcome-banner p {
            margin: 0.3rem 0 0 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Two-Column Form and Grid Section */
        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Box panel layout styling cards */
        .card-panel {
            background-color: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 1.5rem 0;
            color: var(--navy-blue);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form elements design */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1.5px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.15s ease;
        }
        .form-select:focus, .form-textarea:focus {
            border-color: var(--navy-blue);
        }

        /* Drag & Drop Upload Zone */
        .upload-dropzone {
            border: 2px dashed #CBD5E1;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: #F8FAFC;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .upload-dropzone:hover, .upload-dropzone.dragover {
            border-color: var(--navy-blue);
            background-color: #F0F4F8;
        }
        .upload-dropzone i {
            font-size: 2.5rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        .upload-dropzone p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        .upload-dropzone strong {
            color: var(--navy-blue);
        }
        /* Hide the default generic file input button text natively */
        .hidden-file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .file-selected-name {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: var(--success-green);
            font-weight: 600;
            display: none;
        }

        .submit-btn {
            width: 100%;
            background-color: var(--navy-blue);
            color: #FFFFFF;
            border: none;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 0.5rem;
        }
        .submit-btn:hover {
            background-color: #0b1433;
        }

        /* History Table Panels Design Layout */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 0.5rem;
        }
        .history-table th {
            background-color: #F8FAFC;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .history-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        .history-table tr:last-child td {
            border-bottom: none;
        }

        /* Badges status types updates */
        .badge-status {
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge-status.approved {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-status.pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .download-link {
            color: var(--navy-blue);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .download-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header class="top-navbar">
        <a href="#" class="brand-logo">MYIntern</a>
        <div class="user-badge">
            <i class='bx bx-user-circle' style="font-size: 1.5rem;"></i>
            <span><?php echo htmlspecialchars($student['full_name'] ?? 'Student'); ?></span>
        </div>
    </header>

    <main class="workspace-container">
        
        <div class="welcome-banner">
            <h1>Internship Logbook Submission</h1>
            <p>Course: <?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?> | Matric Number: <?php echo htmlspecialchars($student['matric_number'] ?? 'N/A'); ?></p>
        </div>

        <?php if(isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="background-color: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                🎉 Logbook document file submitted successfully for lecturer validation review.
            </div>
        <?php endif; ?>

        <div class="layout-grid">
            
            <section class="card-panel">
                <h2 class="card-title"><i class='bx bx-cloud-upload'></i> Submit New Logbook Entry</h2>
                
                <form action="../actions/process_logbook.php" method="POST" enctype="multipart/form-data" id="logbookForm">
                    
                    <div class="form-group">
                        <label class="form-label" for="week_select">Training Week No.</label>
                        <select class="form-select" name="week_number" id="week_select" required>
                            <option value="" disabled selected>Select current training week</option>
                            <?php for($w=1; $w<=20; $w++): ?>
                                <option value="<?php echo $w; ?>">Week <?php echo $w; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attach Weekly Document (PDF / Word Format)</label>
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

                    <!-- div class="form-group">
                        <label class="form-label" for="activities">Key Activities Summary</label>
                        <textarea class="form-textarea" name="activity_summary" id="activities" rows="5" placeholder="Briefly describe the tasks accomplished and technical operations handled this week..." required></textarea>
                    </div -->

                    <button type="submit" name="submit_logbook" class="submit-btn">Upload Weekly Logbook</button>
                </form>
            </section>

            <!-- section class="card-panel">
                <h2 class="card-title"><i class='bx bx-history'></i> Submission History Ledger</h2>
                
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Week</th>
                                <th>Date Uploaded</th>
                                <th>Attached File</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Week 2</strong></td>
                                <td>May 28, 2026</td>
                                <td><a href="#" class="download-link"><i class='bx bx-file'></i> Week2_Log.pdf</a></td>
                                <td><span class="badge-status pending">Pending Review</span></td>
                            </tr>
                            <tr>
                                <td><strong>Week 1</strong></td>
                                <td>May 21, 2026</td>
                                <td><a href="#" class="download-link"><i class='bx bx-file'></i> Week1_Final.pdf</a></td>
                                <td><span class="badge-status approved">Approved</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section -->

        </div>
    </main>

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