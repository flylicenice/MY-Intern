<?php

if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once("../../includes/db.php");

$companyId = $_SESSION['company_id'];
$query = "SELECT 
    c.company_id,
    c.company_name,
    c.registration_no,
    c.employee_size,
    u.email,
    c.unit,
    c.street,
    c.postal_code,
    c.city,
    c.at_state,
    c.verification_status
FROM company c
INNER JOIN user u ON c.user_id = u.user_id
WHERE c.company_id = ?";

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        header("Location: ../../includes/error.php?error=profile_not_found");
        exit();
    }
} catch (Exception $e) {
    header("Location: ../../includes/error.php?error=database_error");
    exit();
}
?>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<section class="recruitment-workspace">

    <div class="workspace-header-stack">
        <h2 class="page-title">Edit Corporate Profile</h2>
        <p class="page-subtitle">Update your organizational details, registration metrics, and brand logo imagery assets.</p>
    </div>

    <div class="modal-box" style="max-width: 90%; margin-top: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">

        <form id="companyProfileForm" enctype="multipart/form-data">

            <div class="form-row-dual">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($row['company_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="registration_no">Registration Number</label>
                    <input type="text" id="registration_no" name="registration_no" value="<?php echo htmlspecialchars($row['registration_no']); ?>" disabled>
                </div>
            </div>

            <div class="form-row-dual">
                <div class="form-group">
                    <label for="email">Contact Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="employee_size">Employee Size</label>
                    <input type="text" id="employee_size" name="employee_size" value="<?php echo htmlspecialchars($row['employee_size']); ?>" required>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #E2E8F0; margin: 1.5rem 0;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #111E4B; margin: 0 0 1rem 0;">Headquarters Address Details</h3>

            <div class="form-row-dual" style="grid-template-columns: 1fr 2fr;">
                <div class="form-group">
                    <label for="unit">Unit / Suite / Floor</label>
                    <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($row['unit'] ?? ''); ?>" placeholder="e.g. Lot 4-10">
                </div>

                <div class="form-group">
                    <label for="street">Street Address</label>
                    <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($row['street'] ?? ''); ?>" placeholder="e.g. Jalan Tech Avenue">
                </div>
            </div>

            <div class="form-row-dual" style="grid-template-columns: 1fr 1fr 1fr;">
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" maxlength="5" value="<?php echo htmlspecialchars($row['postal_code'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($row['city'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="at_state">State</label>
                    <select id="at_state" name="at_state">
                        <option value="" disabled <?php echo empty($row['at_state']) ? 'selected' : ''; ?>>Select State</option>
                        <?php
                        // Unified collection of Malaysian geographic state registration layers
                        $states = [
                            "Johor",
                            "Kedah",
                            "Kelantan",
                            "Melaka",
                            "Negeri Sembilan",
                            "Pahang",
                            "Penang",
                            "Perak",
                            "Perlis",
                            "Sabah",
                            "Sarawak",
                            "Selangor",
                            "Terengganu",
                            "W.P. Kuala Lumpur",
                            "W.P. Labuan",
                            "W.P. Putrajaya"
                        ];

                        foreach ($states as $state) {
                            // Trim and sanitize contexts to keep comparison tracking entirely clean
                            $selected = (isset($row['at_state']) && trim($row['at_state']) === $state) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($state) . '" ' . $selected . '>' . htmlspecialchars($state) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer-actions">
                <button type="submit" id="saveProfileBtn" class="btn-submit-save">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</section>