<?php
// Adjust to your actual DB relative path location
require_once 'db.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Retrieve the logbook ID parameter safely
$logbook_id = $_GET['logbook_id'] ?? '';

if (empty($logbook_id)) {
    die("Error: Missing logbook identification parameter.");
}

$logbook_id = mysqli_real_escape_string($conn, $logbook_id);

// 2. Query the raw binary BLOB data directly from the table
$log_query = "SELECT week_number, logbook FROM logbook WHERE logbook_id = '$logbook_id' LIMIT 1";
$log_res = $conn->query($log_query);

if (!$log_res || $log_res->num_rows === 0) {
    die("Error: Logbook record tracking index does not exist.");
}

$log_file = $log_res->fetch_assoc();
$pdf_blob_data = $log_file['logbook']; // This holds the raw binary data stream
$week_num = $log_file['week_number'];

// 3. Fallback safety verification check to confirm data content exists
if (empty($pdf_blob_data)) {
    die("Error: The requested logbook record is empty or does not contain a valid PDF data stream.");
}

// 4. Clear output buffers to prevent any binary data stream contamination
if (ob_get_level()) {
    ob_end_clean();
}

// 5. Inject HTTP content delivery headers for a seamless inline browser view
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Week_' . sprintf("%02d", $week_num) . '_Logbook.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
header('Content-Length: ' . strlen($pdf_blob_data)); // Get length directly from the data string

// 6. Print the binary BLOB data straight to the output buffer stream
echo $pdf_blob_data;
exit;