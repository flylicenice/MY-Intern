<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication Guard
if (!isset($_SESSION['user_id'])) {
    die("Access Denied: Please log in first.");
}

require_once("db.php");

try {
    // Fetch the binary resume blob for the logged-in user
    $query = "SELECT resume FROM student WHERE user_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && !empty($res['resume'])) {
        $pdfBlob = $res['resume'];

        // 1. Clear any previous buffer outputs to prevent file corruption
        ob_clean();

        // 2. Set headers to tell the browser this is a PDF document
        header("Content-Type: application/pdf");
        
        // 3. "inline" forces the browser to open it in a tab instead of downloading it
        header("Content-Disposition: inline; filename=\"resume_preview.pdf\"");
        header("Content-Length: " . strlen($pdfBlob));

        // 4. Output the raw binary data stream
        echo $pdfBlob;
        exit();
    } else {
        echo "No resume document found for this account.";
    }
} catch (Exception $e) {
    echo "Error loading document workflow.";
}