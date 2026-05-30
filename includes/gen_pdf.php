<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Load Dompdf & Set Options
$dompdf = new Dompdf();

// 2. Capture the Invoice Template Output
ob_start();
include 'template.php'; // This file uses the variables from above
$html = ob_get_clean();

// 3. Load and Render the HTML
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 4. Stream or Save the PDF
$dompdf->stream('invoice.pdf', ['Attachment' => false]);
// Or to force download: $dompdf->stream('invoice.pdf');