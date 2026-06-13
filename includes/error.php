<?php

$error = $_GET['error'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MYIntern | Error</title>
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

        h3#error-msg {
            color: red;
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
    <h3 id="error-msg"><?php if(isset($error)) {
        echo $error; 
        } ?>
    </h3>
    <a id="home-btn" href="javascript:history.back()">Back</a>
</body>
</html>
