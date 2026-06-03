<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYIntern | Join Us</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="signup-body">

    <div class="signup-split-container">
        
        <aside class="role-selection-sidebar" id="sign-up-sidebar">
            <h1 class="sidebar-logo">MYIntern</h1>
            
            <div class="role-toggle-group">
                <p class="toggle-heading">Are you a...</p>
                
                <button type="button" class="role-nav-btn active" data-target-role="Student">
                    Student
                </button>
                
                <p class="toggle-heading secondary-heading">Or start hiring here!</p>
                
                <button type="button" class="role-nav-btn" data-target-role="Company">
                    Employer
                </button>
            </div>

            <div style="margin-top: 3rem; text-align: center;">
                <p>Already have an account?</p>
                <br>
                <strong><a style="color: #5DF8D8;" href="login.php">Login here</a></strong>
            </div>
        </aside>

        <main class="signup-form-canvas">
            <div class="form-scroll-wrapper">
                <h2 class="form-title">Join MYIntern!</h2>
                
                <form action="../actions/sign_up_process.php" method="POST" id="registrationForm">
                    
                    <input type="hidden" name="role" id="userRoleInput" value="Student">

                    <div class="form-grid">
                        <div class="input-block full-width">
                            <input type="email" name="email" placeholder="Email Address" required>
                        </div>
                    </div>


                    <div class="role-conditional-fields active" id="fields-Student">
                        <div class="form-grid">
                            <div class="input-block full-width">
                                <input type="text" name="fullname" placeholder="Full Name" required>
                            </div>
                            <div class="input-block full-width">
                                <input type="tel" name="phone_number" placeholder="Phone Number" required>
                            </div>
                            <div class="input-block">
                                <input type="text" name="matric_no" placeholder="Matric No." required>
                            </div>
                            <div class="input-block">
                                <input type="text" name="ic_no" placeholder="IC No." required>
                            </div>
                            <div class="input-block full-width">
                                <div class="select-wrapper">
                                    <select name="course" required>
                                        <option value="" disabled selected>Course</option>
                                        <option value="DCS">Diploma Computer Science (DCS)</option>
                                        <option value="BITC">Bachelor In Computer Networking (BITC)</option>
                                        <option value="BITD">Bachelor In Database Management (BITD)</option>
                                        <option value="BITS">Bachelor In Software Development (BITS)</option>
                                        <option value="BITE">Bachelor In Game Technology (BITE)</option>
                                        <option value="BITM">Bachelor In Interactive Media (BITM)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="role-conditional-fields" id="fields-Company">
                        <div class="form-grid">
                            <div class="input-block full-width">
                                <input type="text" name="registration_no" placeholder="Company Registration No.">
                            </div>
                            <div class="input-block full-width">
                                <input type="text" name="company_name" placeholder="Company Name">
                            </div>
                            <div class="input-block full-width">
                                <input type="text" name="employee_size" placeholder="Employee Size e.g.: [100 - 200]">
                            </div>
                            <div class="input-block">
                                <input type="text" name="unit" placeholder="Unit">
                            </div>
                            <div class="input-block">
                                <input type="text" name="street" placeholder="Street">
                            </div>
                            <div class="input-block">
                                <input type="text" name="postcode" placeholder="Postal Code">
                            </div>
                            <div class="input-block">
                                <input type="text" name="city" placeholder="City">
                            </div>
                            <div class="input-block full-width">
                                <div class="select-wrapper">
                                    <select name="state" required>
                                        <option value="" disabled selected>State</option>
                                        <option value="Johor">Johor</option>
                                        <option value="Kedah">Kedah</option>
                                        <option value="Kelantan">Kelantan</option>
                                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                                        <option value="Labuan">Labuan</option>
                                        <option value="Melaka">Melaka</option>
                                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                                        <option value="Pahang">Pahang</option>
                                        <option value="Penang">Penang</option>
                                        <option value="Perak">Perak</option>
                                        <option value="Perlis">Perlis</option>
                                        <option value="Putrajaya">Putrajaya</option>
                                        <option value="Sabah">Sabah</option>
                                        <option value="Sarawak">Sarawak</option>
                                        <option value="Selangor">Selangor</option>
                                        <option value="Terengganu">Terengganu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-grid security-grid">
                        <div class="input-block">
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="input-block">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                        </div>
                    </div>

                    <div class="form-action-row">
                        <button type="submit" name="submit_register" class="signup-submit-btn">Register</button>
                    </div>

                </form>
            </div>
        </main>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const roleButtons = document.querySelectorAll(".role-nav-btn");
            const roleInput = document.getElementById("userRoleInput");
            const allConditionalBlocks = document.querySelectorAll(".role-conditional-fields");

            roleButtons.forEach(button => {
                button.addEventListener("click", function() {
                    // Remove active style marker from previous button
                    roleButtons.forEach(btn => btn.classList.remove("active"));
                    
                    // Activate clicked option styling
                    this.classList.add("active");
                    
                    // Grab current value string target (Student, Lecturer, or Company)
                    const selectedRole = this.getAttribute("data-target-role");
                    roleInput.value = selectedRole;

                    // Hide all conditional form sections completely
                    allConditionalBlocks.forEach(block => {
                        block.classList.remove("active");
                        
                        // Disable underlying inputs so they aren't marked as "required" when hidden
                        block.querySelectorAll("input, select").forEach(input => {
                            input.removeAttribute("required");
                        });
                    });

                    // Activate targeted view blocks
                    const targetBlock = document.getElementById(`fields-${selectedRole}`);
                    if (targetBlock) {
                        targetBlock.classList.add("active");
                        
                        // Turn required markers back on for user entries
                        targetBlock.querySelectorAll("input, select").forEach(input => {
                            input.setAttribute("required", "required");
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>