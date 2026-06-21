<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='../../css/unistyle.css' rel="stylesheet">
    <script src="../../js/admin.js"></script>
    <title>Add Admin</title>
</head>

<body>
    <div class="form-modal-overlay">
        <div class="form-modal-container">

            <form id="addAdminForm" method="POST" action="../../includes/add_admin_process.php">
                <div class="modal-input-grid">
                    <div class="input-block">
                        <label for="staff_id">Staff ID</label>
                        <input type="text" id="staff_id" name="staff_id" placeholder="e.g. L001" required>
                    </div>

                    <div class="input-block">
                        <label for="admin_email">Email Address</label>
                        <input type="email" id="admin_email" name="admin_email" placeholder="name@utem.edu.my" required>
                    </div>

                    <div class="input-block">
                        <label for="password">Password</label>
                        <input type="password" id="admin_password" name="admin_password" placeholder="Password" required>
                        <p class="error-text" id="adminPasswordError" style="color: red; display: none; font-size: 10px;"></p>
                    </div>
                </div>

                <div class="form-modal-footer">
                    <button type="button" class="btn-cancel" id="closeAdminWindow">Cancel</button>
                    <button type="submit" class="btn-save-submit" id="saveAdminBtn">Add</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>