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
    <title>Add Lecturer</title>
</head>

<body>
    <div class="form-modal-overlay" id="lecturerModalOverlay">
        <div class="form-modal-container">

            <form id="addLecturerForm" method="POST">
                <div class="modal-input-grid">
                    <div class="input-block">
                        <label for="lec_id">Staff ID</label>
                        <input type="text" id="staff_id" name="staff_id" placeholder="e.g., L005" required>
                    </div>

                    <div class="input-block">
                        <label for="lec_name">Full Name</label>
                        <input type="text" id="lec_name" name="lecturer_name" placeholder="Enter name" required>
                    </div>

                    <div class="input-block">
                        <label for="lec_ic">Identification Number</label>
                        <input type="text" id="lec_ic" name="lecturer_ic" placeholder="Identification No." required>
                    </div>

                    <div class="input-block">
                        <label for="lec_ic">Phone Number</label>
                        <input type="text" id="lec_phone" name="lecturer_phone" placeholder="Phone No." required>
                    </div>

                    <div class="input-block">
                        <label for="lec_email">Email Address</label>
                        <input type="email" id="lec_email" name="lecturer_email" placeholder="name@utem.edu.my" required>
                    </div>

                    <div class="input-block">
                        <label for="password">Password</label>
                        <input type="password" id="lec_password" name="lecturer_password" placeholder="Password" required>
                    </div>
                </div>

                <div class="form-modal-footer">
                    <button type="button" class="btn-cancel" id="closeLecturerModalCancel">Cancel</button>
                    <button type="submit" class="btn-save-submit">Save Lecturer</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>