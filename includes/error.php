<?php
// Get the status code from the server
$status = $_SERVER['REDIRECT_STATUS'] ?? 404;

// Define friendly messages for common errors
$codes = [
    403 => ['Forbidden', 'You don\'t have permission to access this page.'],
    404 => ['Page Not Found', 'The page you are looking for doesn\'t exist.'],
    500 => ['Internal Server Error', 'Something went wrong on our end.'],
];

// Fallback for unknown codes
$title = $codes[$status][0] ?? "Error $status";
$message = $codes[$status][1] ?? "An unexpected error occurred.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            margin-left: 20%;
            margin-right: 20%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-family: "Google Sans", sans-serif;
        }

        h1, h2, h3 {
            line-height: 0.2;
        }

        h3 {
            font-size: 2rem;
        }

        h2 {
            font-size: 4rem;
        }

        h1 {
            font-size: 6rem;
        }

        a#home-btn{
            display: block;
            padding: 2rem;
            margin-right: 80%;
            border-radius: 12px;
            background-color: #00ADB5;
            font-weight: 800;
            color: white;
            text-decoration: none;

            transition: background-color 0.2s ease-in;
        }

        a#home-btn:hover {
            background-color: #111844;
        }
    </style>
</head>
<body>
    <h3>MYIntern</h3>
    <h1>Oops!</h1>
    <h2>Looks like there is an error.</h2>
    <a id="home-btn" href="/MyIntern">Back to Home</a>
</body>
</html>
