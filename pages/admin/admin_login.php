<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MYIntern Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../../js/script.js"></script>
    <link rel="stylesheet" href="../../css/unistyle.css">
</head>

<body class="auth-body">

    <header class="auth-header">
        <h1 class="brand-logo">MYIntern</h1>
        <span class="brand-subtext">Admin</span>
    </header>

    <main class="login-card-container">
        <h2 class="card-title">Welcome Back!</h2>

        <form action="../actions/login_process.php" method="POST" class="login-form">

            <div class="field-wrapper">
                <input type="email" name="email" id="email" placeholder="Email" required autocomplete="email">
            </div>

            <div class="field-wrapper password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class='bx bx-show password-toggle-icon' id="togglePassword"></i>
            </div>

            <button type="submit" name="submit_login" class="login-submit-btn">Login</button>

        </form>
    </main>
</body>

</html>