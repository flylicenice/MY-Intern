<?php

$companies = [
    [
        'id' => 'C001',
        'name' => 'Acis Technology',
        'sector' => 'Engineering',
        'contact_person' => 'Ali Smith',
        'email' => 'ASmith@acis.com',
        'status' => 'verified'
    ],
    [
        'id' => 'C002',
        'name' => 'SoftInn',
        'sector' => 'Technology',
        'contact_person' => 'Lara Carlson',
        'email' => 'lara@softinn.com',
        'status' => 'pending'
    ],
    [
        'id' => 'C003',
        'name' => 'TNB',
        'sector' => 'Engineering',
        'contact_person' => 'John',
        'email' => 'john@tnb.com',
        'status' => 'rejected'
    ],
    [
        'id' => 'C004',
        'name' => 'TechCorp',
        'sector' => 'Technology',
        'contact_person' => 'Darla Sophie',
        'email' => 'sophie@techcorp.com',
        'status' => 'verified'
    ],
     [
        'id' => 'C005',
        'name' => 'FoodTech',
        'sector' => 'Manufacturing and Science Technology',
        'contact_person' => 'Lauren James',
        'email' => 'lauren@foodtech.com',
        'status' => 'verified'
    ],
];
?>

<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Company</h1>
            <p>Total companies : <span id="company-total-count"><?php echo count($companies); ?></span> companies</p>
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

        <div class="details-card" style="margin-top: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; padding: 20px;">
            <h2 class="table-title" style="color: #1e3a8a; margin-bottom: 15px;">Company Details</h2>
            
            <div class="top-bar" style="margin-bottom: 15px;">
                <input type="text" id="tableSearchInput" placeholder="Search by name, ID..." onkeyup="filterCompanyTable()" style="padding: 8px 12px; width: 280px; border: 1px solid #cbd5e0; border-radius: 4px;">
            </div>

            <div class="table-responsive">
                <table id="companyTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px; color: #1e3a8a;">COMPANY ID</th>
                            <th style="padding: 12px; color: #1e3a8a;">COMPANY NAME</th>
                            <th style="padding: 12px; color: #1e3a8a;">SECTOR</th>
                            <th style="padding: 12px; color: #1e3a8a;">CONTACT PERSON</th>
                            <th style="padding: 12px; color: #1e3a8a;">EMAIL</th>
                            <th style="padding: 12px; color: #1e3a8a;">STATUS</th>
                            <th style="padding: 12px; color: #1e3a8a;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr id="null-state-row" style="display: none;">
                            <td colspan="7" style="text-align: center; color: #a0aec0; padding: 25px;">No Company for now</td>
                        </tr>

                        <?php foreach ($companies as $company): ?>
                            <tr class="company-data-row" data-status="<?php echo $company['status']; ?>" style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding: 12px;"><?php echo $company['id']; ?></td>
                                <td class="company-name" style="padding: 12px; font-weight: 500;"><?php echo $company['name']; ?></td>
                                <td style="padding: 12px;"><?php echo $company['sector']; ?></td>
                                <td style="padding: 12px;"><?php echo $company['contact_person']; ?></td>
                                <td style="padding: 12px;"><?php echo $company['email']; ?></td>
                                <td style="padding: 12px;">
                                    <span class="status-badge <?php echo $company['status']; ?>">
                                        <?php echo $company['status']; ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    <?php if ($company['status'] === 'pending'|| $company['status'] === 'rejected'): ?>
                                        <button class="action-btn btn-verify" onclick="verifyCompany('<?php echo $company['id']; ?>')" >Verify</button>
                                    <?php else: ?>
                                        <button class="action-btn btn-verify" disabled>Verify</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script>
function toggleCompanyFilterMenu() {
    const container = document.getElementById('company-filter-container');
    container.style.display = (container.style.display === 'none' || container.style.display === '') ? 'block' : 'none';
}

function filterCompanyTable() {
    const searchFieldQuery = document.getElementById('tableSearchInput').value.toLowerCase();
    const checkedRadioOption = document.querySelector('input[name="filter"]:checked');
    const statusConstraint = checkedRadioOption ? checkedRadioOption.value : 'all';
    
    const operationalRows = document.querySelectorAll('.company-data-row');
    let visibleMatchCounter = 0;

    operationalRows.forEach(row => {
        if (row.id === 'null-state-row') return;

        const companyId = row.cells[0].innerText.toLowerCase();
        const companyName = row.cells[1].innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');

        const matchesStatus = (statusConstraint === 'all' || rowStatus === statusConstraint);
        const matchesSearch = (companyId.includes(searchFieldQuery) || companyName.includes(searchFieldQuery));

        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleMatchCounter++;
        } else {
            row.style.display = 'none';
        }
    });

    const nullRow = document.getElementById('null-state-row');
    if (nullRow) {
        nullRow.style.display = (visibleMatchCounter === 0) ? '' : 'none';
    }
    document.getElementById('company-total-count').innerText = visibleMatchCounter;

    
}

function verifyCompany(companyId) {
    const rows = document.querySelectorAll('.company-data-row');

    rows.forEach(row => {
        if (row.cells[0].innerText.trim() === companyId) {
            targetRow = row;
        }
    });

    if (targetRow) {
        targetRow.setAttribute('data-status', 'verified');

        const badge = targetRow.querySelector('.status-badge');
        if (badge) {
            badge.className = 'status-badge verified';
            badge.innerText = 'verified';
        }

        const verifyBtn = targetRow.querySelector('.btn-verify');
        if (verifyBtn) {
            verifyBtn.disabled = true;
        }

        filterCompanyTable();
    }
                                    
}
</script>