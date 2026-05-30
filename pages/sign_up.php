<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>MYIntern | Sign Up</title>
</head>

<body class="center">
    <main class="main-content">
        <form action="../actions/sign_up_process.php" method="POST">
            <div class="input-wrapper">
                <input class="basic-textfield" type="email" type="email" placeholder="Email">
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="password" name="password" placeholder="Password">
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="password" name="confirm-password" placeholder="Confirm Password">
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="text" name="matric-no" placeholder="Matric Number">
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="text" name="full-name" placeholder="Full Name">
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="tel" name="phone-no" placeholder="Phone Number"
            </div>

            <div class="input-wrapper">
                <input class="basic-textfield" type="number" step="0.01" min="0" max="4" name="cgpa" placeholder="CGPA">
            </div>

            <div class="input-wrapper">
                <select name="course">
                    <option value="DCS">Diploma Computer Science (DCS)</option>
                    <option value="BITC">Bachelor In Computer Networking (BITC)</option>
                    <option value="BITD">Bachelor In Database Management (BITD)</option>
                    <option value="BITS">Bachelor In Software Development (BITS)</option>
                    <option value="BITE">Bachelor In Game Technology (BITE)</option>
                    <option value="BITM">Bachelor In Interactive Media (BITM)</option>
                </select>
            </div>

            <button name="sign-up-btn">Sign Up</button>
        </form>
    </main>
</body>

</html>