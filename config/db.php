<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-intern";


try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        header("Location: /MyIntern/pages/error.php");
        exit();
    }
} catch (Exception $e) {
    header("Location: /MyIntern/pages/error.php");
    exit();
}
