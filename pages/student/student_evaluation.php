<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php"); // Fixed the dot to a slash typo

$matricNo = $_SESSION['matric_number'] ?? '';

$query = "SELECT evaluation_id, evaluation_file FROM evaluation WHERE matric_number = ? LIMIT 1";
$evaluation_id = null;
$hasEvaluation = false;

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $matricNo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Confirm the BLOB data field is not null or empty
        if (!empty($row['evaluation_file'])) {
            $hasEvaluation = true;
            $evaluation_id = $row['evaluation_id'];
        }
    }
    $stmt->close();
} catch (Exception $e) {
    header("Location: ../../includes/error.php?database_error");
    exit();
}
?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<main class="dashboard-content-wrapper">
    <div class="evaluation-outer-card">
        
        <h2 class="student-greeting-title">Hi <?php echo isset($_SESSION['display_name']) ? htmlspecialchars($_SESSION['display_name']) : 'Student'; ?></h2>
        
        <div class="evaluation-inner-container">
            <?php if ($hasEvaluation): ?>
                <p class="empty-state-notice" style="color: #0d9488; font-weight: 500;">Your final evaluation form has been uploaded by your lecturer.</p>
                <h1>TAP THE BUTTON BELOW TO VIEW YOUR EVALUATION.</h1>
                
                <div class="button-alignment-row">
                    <a href="../../includes/student_view_evaluation.php?evaluation_id=<?php echo $evaluation_id; ?>" target="_blank">
                        <button type="button" class="download-pdf-btn" style="cursor: pointer; background-color: #0f172a; color: #fff;">
                            Download PDF
                        </button>
                    </a>
                </div>
            <?php else: ?>
                <p class="empty-state-notice">There is no evaluation here for now.</p>
                
                <div class="button-alignment-row">
                    <button type="button" class="download-pdf-btn" disabled>
                        Download PDF
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>