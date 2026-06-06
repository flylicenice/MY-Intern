<?php
//require_once '../../includes/session.php';

$lecturers = [
    [
        'id' => 'L011',
        'name' => 'Nor Haslinda Binti Ismail',
        'department' => 'FTMK',
        'status' => 'active',
        'email' => 'lynda@utem.edu.my',
        'phone' => '+606 270 2485',
        
    ],
    [
        'id' => 'L002',
        'name' => 'Nuzulha Khilwani Ibrahim ',
        'department' => 'FTMK',
        'status' => 'on leave',
        'email' => 'nuzulha@utem.edu.my',
        'phone' => '+606 270 2443',
        
    ],
    [
        'id' => 'L003',
        'name' => 'Ahmad bin Bakar',
        'department' => 'FTMK',
        'status' => 'active',
        'email' => 'ahmad@utem.edu.my',
        'phone' => '+606 270 2444',
        
    ],
    [
        'id' => 'L004',
        'name' => ' Salmah Binti Zainal',
        'department' => 'FTMK',
        'status' => 'on leave',
        'email' => 'salmah@utem.edu.my',
        'phone' => '+606 270 2445',
        
    ]

];
?>

<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Lecturer</h1>
            <p>Total lecturers : <span id="lecturer-total-count"><?php echo count($lecturers); ?></span> lecturers</p>
        </div>
    </div>
<section class="data-table-section">
    <div class="top-bar">
            <input type="text" placeholder="Search by name, matric no...">
        </div>
        <div class="absolute-relative-container">
            <div class="btn-container top-bar">
                <button class="action-btn" id="filter-btn" onclick="toggleLecturerFilterMenu()">Filter</button>
                <button class="action-btn btn-add" onclick="openAddLecturerModal()" style="background-color: #1e3a8a; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">
                  <i class='bx bx-plus'></i> Add Lecturer
                </button>
            </div>

            <div class="drop-down-container" id="lecturer-filter-container" style="display: none;">
                <div class="option-container">
                    <input id="all-rb" type="radio" value="all" name="filter" checked onclick="filterLecturerTable()">
                    <label for="all-rb">All Statuses</label>
                </div>
                <div class="option-container">
                    <input id="active-rb" type="radio" value="active" name="filter" onclick="filterLecturerTable()">
                    <label for="active-rb">Active</label>
                </div>
                <div class="option-container">
                    <input id="leave-rb" type="radio" value="on leave" name="filter" onclick="filterLecturerTable()">
                    <label for="leave-rb">On Leave</label>
                </div>
            </div>
        </div>

        <div class="details-card" style="margin-top: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; padding: 20px;">
            <h2 class="table-title" style="color: #1e3a8a; margin-bottom: 15px;">Lecturers Details</h2>
            
            <div class="top-bar" style="margin-bottom: 15px;">
                <input type="text" id="tableSearchInput" placeholder="Search by name, ID..." onkeyup="filterLecturerTable()" style="padding: 8px 12px; width: 280px; border: 1px solid #cbd5e0; border-radius: 4px;">
            </div>

            <div class="table-responsive">
                <table id="lecturerTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px; color: #1e3a8a;">LECTURER ID</th>
                            <th style="padding: 12px; color: #1e3a8a;">LECTURER NAME</th>
                            <th style="padding: 12px; color: #1e3a8a;">DEPARTMENT</th>
                            <th style="padding: 12px; color: #1e3a8a;">EMAIL</th>
                            <th style="padding: 12px; color: #1e3a8a;">STATUS</th>
                            <th style="padding: 12px; color: #1e3a8a;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="null-state-row" style="display: none;">
                            <td colspan="6" style="text-align: center; color: #a0aec0; padding: 25px;">No Lecturer for now</td>
                        </tr>

                        <?php foreach ($lecturers as $lecturer): ?>
                            <tr class="lecturer-data-row" data-status="<?php echo $lecturer['status']; ?>" style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding: 12px;"><?php echo $lecturer['id']; ?></td>
                                <td class="lecturer-name" style="padding: 12px; font-weight: 500;"><?php echo $lecturer['name']; ?></td>
                                <td style="padding: 12px;"><?php echo $lecturer['department']; ?></td>
                                <td style="padding: 12px;"><?php echo $lecturer['email']; ?></td>
                                <td style="padding: 12px;">
                                    <span class="status-badge <?php echo ($lecturer['status'] === 'active') ? 'active' : 'on-leave'; ?>">
                                        <?php echo strtoupper($lecturer['status']); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    <button class="action-btn btn-view" onclick='openFacultyDrawer(<?php echo json_encode($lecturer); ?>)'>View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="details-section" id="facultyDetailsDrawer" style="display: none; position: fixed; top: 0; right: 0; width: 380px; height: 100%; background: #fff; box-shadow: -4px 0 15px rgba(0,0,0,0.1); padding: 40px 25px; z-index: 999;">
        <button class="action-btn" id="closeDetailsBtn" type="button" onclick="closeFacultyDrawer()" style="position: absolute; top: 15px; left: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        
        <div class="top-layer-container" style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px; margin-top: 20px;">
            <div class="profile-container">
                <img src="../../assets/default-user.svg" alt="user-profile" width="96" height="96" style="border-radius: 50%;">
            </div>
            <div class="personal-info-container">
                <p class="bold main" id="drawerName" style="font-weight: 700; font-size: 1.1rem;"></p>
                <p class="bold grey" id="drawerId" style="color: #718096; font-size: 0.9rem;"></p>
                <p class="small grey" id="drawerEmail" style="color: #a0aec0; font-size: 0.85rem;"></p>
                <p class="small grey" id="drawerPhone" style="color: #a0aec0; font-size: 0.85rem;"></p>
            </div>
        </div>

        <div class="info-container">
            <div class="block-container" style="margin-bottom: 20px;">
                <h1 style="font-size: 0.8rem; color: #4a5568; letter-spacing: 0.5px; margin-bottom: 5px;">FACULTY ACADEMIC INFO</h1>
                <p class="bold" id="drawerFaculty" style="font-weight: 600; font-size: 0.9rem; color: #2d3748;"></p> 
            </div>
            <div class="block-container">
                <h1 style="font-size: 0.8rem; color: #4a5568; letter-spacing: 0.5px; margin-bottom: 5px;">DEPARTMENT ASSIGNMENT</h1>
                <p class="bold" style="font-weight: 600; font-size: 0.9rem; color: #2d3748;">Department Unit: <span id="drawerDept" style="font-weight: normal; color: #718096;"></span></p>
            </div>
        </div>
    </section>
</main>

<script>
function toggleLecturerFilterMenu() {
    const container = document.getElementById('lecturer-filter-container');
    container.style.display = (container.style.display === 'none' || container.style.display === '') ? 'block' : 'none';
}

function filterLecturerTable() {
    const searchFieldQuery = document.getElementById('tableSearchInput').value.toLowerCase();
    const checkedRadioOption = document.querySelector('input[name="filter"]:checked');
    const statusConstraint = checkedRadioOption ? checkedRadioOption.value : 'all';
    
    const operationalRows = document.querySelectorAll('.lecturer-data-row');
    let visibleMatchCounter = 0;

    operationalRows.forEach(row => {
        const rowStatusAttr = row.getAttribute('data-status');
        const cellLecturerName = row.querySelector('.lecturer-name').innerText.toLowerCase();
        const cellStaffId = row.cells[0].innerText.toLowerCase();

        const matchesStatus = (statusConstraint === 'all' || rowStatusAttr === statusConstraint);
        const matchesSearch = (cellLecturerName.includes(searchFieldQuery) || cellStaffId.includes(searchFieldQuery));

        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleMatchCounter++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('null-state-row').style.display = (visibleMatchCounter === 0) ? '' : 'none';
    document.getElementById('lecturer-total-count').innerText = visibleMatchCounter;
}

function openFacultyDrawer(data) {
    document.getElementById('drawerName').innerText = data.name || 'N/A';
    document.getElementById('drawerId').innerText = data.id || 'N/A';
    document.getElementById('drawerEmail').innerText = data.email || 'N/A';
    document.getElementById('drawerPhone').innerText = data.phone || 'Not Provided';
    document.getElementById('drawerFaculty').innerText = data.faculty || 'Faculty of Information and Communication Technology';
    document.getElementById('drawerDept').innerText = data.department || 'N/A';
    document.getElementById('facultyDetailsDrawer').style.display = 'block';
    document.body.style.overflow = 'hidden'; 
    
    
}

function closeFacultyDrawer() {
    document.getElementById('facultyDetailsDrawer').style.display = 'none';
    document.body.style.overflow = 'auto';
}

</script>