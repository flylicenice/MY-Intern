<?php 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<main class="dashboard-content-wrapper">
    <div class="evaluation-outer-card">
        
        <h2 class="student-greeting-title">Hi <?php echo isset($_SESSION['display_name'])?htmlspecialchars
        ($_SESSION['display_name']) : 'Student'; ?></h2>
        
        <div class="evaluation-inner-container">
            <p class="empty-state-notice">There is no evaluation here for now.</p>
            
            <div class="button-alignment-row">
                <button type="button" class="download-pdf-btn" disabled>
                    Download PDF
                </button>
            </div>
        </div>

    </div>
</main>