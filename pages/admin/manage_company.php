<?php

require_once("../../includes/db.php");

try {
    $selectCompanyQuery = "SELECT * FROM user u INNER JOIN company c ON u.user_id = c.user_id";
    $result = $conn->query($selectCompanyQuery);

    $countQuery = "SELECT COUNT(*) AS total_company FROM company";
    $countResult = $conn->query($countQuery);

    if ($countResult) {
        $countRow = $countResult->fetch_assoc();
        $totalCompany = $countRow['total_company'];
    }
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}

?>

<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Company</h1>
            <p>Total companies : <span id="company-total-count"><?php echo $totalCompany; ?></span> companies</p>
        </div>
    </div>

    <section class="data-table-section">
        <div class="absolute-relative-container" style="position: relative;">
            <div class="btn-container" style="display: flex; gap: 10px; margin-bottom: 15px;">
                <button class="action-btn" id="filter-btn" onclick="toggleCompanyFilterMenu()">Filter</button>
            </div>

            <div class="drop-down-container" id="company-filter-container" style="display: none; position: absolute; top: 45px; left: 0; background: white; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); z-index: 100;">
                <div class="option-container" style="margin-bottom: 8px;">
                    <input id="all-rb" type="radio" value="all" name="filter" checked onclick="filterCompanyTable()">
                    <label for="all-rb" style="margin-left: 5px; cursor: pointer;">All Statuses</label>
                </div>
                <div class="option-container" style="margin-bottom: 8px;">
                    <input id="verified-rb" type="radio" value="verified" name="filter" onclick="filterCompanyTable()">
                    <label for="verified-rb" style="margin-left: 5px; cursor: pointer;">Verified</label>
                </div>
                <div class="option-container" style="margin-bottom: 8px;">
                    <input id="pending-rb" type="radio" value="pending" name="filter" onclick="filterCompanyTable()">
                    <label for="pending-rb" style="margin-left: 5px; cursor: pointer;">Pending</label>
                </div>
                <div class="option-container">
                    <input id="rejected-rb" type="radio" value="rejected" name="filter" onclick="filterCompanyTable()">
                    <label for="rejected-rb" style="margin-left: 5px; cursor: pointer;">Rejected</label>
                </div>
            </div>
        </div>

        <div class="details-card">
            <h2 class="table-title" style="margin-bottom: 15px;">Company Details</h2>

            <div class="top-bar" style="margin-bottom: 15px;">
                <input type="text" id="tableSearchInput" placeholder="Search by name, ID..." onkeyup="filterCompanyTable()" style="padding: 8px 12px; width: 280px; border: 1px solid #cbd5e0; border-radius: 4px;">
            </div>

            <div class="table-responsive">
                <table id="companyTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px;">COMPANY ID</th>
                            <th style="padding: 12px;">COMPANY NAME</th>
                            <th style="padding: 12px;">EMAIL</th>
                            <th style="padding: 12px;">STATUS</th>
                            <th style="padding: 12px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0) : ?>
                            <?php while ($company = $result->fetch_assoc()): ?>
                                <tr class="company-data-row" 
                                data-status="<?php echo $company['status']; ?>"
                                data-company-id="<?php echo $company['company_id']?>" style="border-bottom: 1px solid #edf2f7;">
                                    <td style="padding: 12px;"><?php echo $company['company_id']; ?></td>
                                    <td class="company-name" style="padding: 12px; font-weight: 500;"><?php echo $company['company_name']; ?></td>
                                    <td style="padding: 12px;"><?php echo $company['email']; ?></td>
                                    <td style="padding: 12px;">
                                        <span class="status-badge <?php echo $company['verification_status']; ?>"><?php echo $company["verification_status"]; ?></span>
                                    </td>
                                    <td style="padding: 12px;">
                                        <?php if ($company['verification_status'] === 'pending'): ?>
                                            <button class="action-btn btn-verify" onclick="verifyCompany(this, '<?php echo $company['company_id']; ?>')">Verify</button>
                                        <?php else: ?>
                                            <button class="action-btn btn-verify" disabled>Verify</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="null-state-row" style="display: none;">
                                <td colspan="7" style="text-align: center; color: #a0aec0; padding: 25px;">No Company for now</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>