<?php

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
            <h1>Manage Admin</h1>
        </div>
    </div>
    <section class="data-table-section">
        <div class="absolute-relative-container">
            <div class="btn-container top-bar">
                <button class="action-btn" id="filter-btn">Filter</button>
                <button class="action-btn btn-add" onclick="openAddAdminWindow()" style="background-color: #1e3a8a; color: white; border: none;">
                    <i class='bx bx-plus'></i> Add Admin
                </button>
            </div>

            <div class="drop-down-container" id="filter-container">
                <div class="option-container">
                    <input id="all-rb" type="radio" value="all" name="filter" checked onclick="filterAdminrTable()">
                    <label for="all-rb">All Statuses</label>
                </div>
                <div class="option-container">
                    <input id="active-rb" type="radio" value="active" name="filter" onclick="filterAdminTable()">
                    <label for="active-rb">Active</label>
                </div>
                <div class="option-container">
                    <input id="leave-rb" type="radio" value="on leave" name="filter" onclick="filterAdminTable()">
                    <label for="leave-rb">On Leave</label>
                </div>
            </div>
        </div>

        <div class="details-card">
            <h2 class="table-title">Admin Details</h2>

            <div class="top-bar">
                <input type="text" id="tableSearchInput" placeholder="Search by name, ID..." onkeyup="filterLecturerTable()">
            </div>

            <div class="table-responsive">
                <table id="lecturerTable">
                    <thead>
                        <tr>
                            <th>ADMIN ID</th>
                            <th>STAFF ID</th>
                            <th>EMAIL</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="null-state-row">
                            <td colspan="6">No Lecturer for now</td>
                        </tr>

                        <?php foreach ($lecturers as $lecturer): ?>
                            <tr class="lecturer-data-row" data-status="<?php echo $lecturer['status']; ?>">
                                <td><?php echo $lecturer['id']; ?></td>
                                <td class="lecturer-name"><?php echo $lecturer['name']; ?></td>
                                <td> <?php echo $lecturer['email']; ?></td>
                                <td style="padding: 12px;">
                                    <button class="action-btn btn-reject">Delete</button>
                                    <button class="action-btn btn-send-mail">Reset Password</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="details-section" id="facultyDetailsDrawer">
        <button class="action-btn" id="closeDetailsBtn" type="button" onclick="closeFacultyDrawer()">&times;</button>

        <div class="top-layer-container">
            <div class="profile-container">
                <img src="../../assets/default-user.svg" alt="user-profile" width="96" height="96">
            </div>
            <div class="personal-info-container">
                <p class="bold main" id="drawerName"></p>
                <p class="bold grey" id="drawerId"></p>
                <p class="small grey" id="drawerEmail"></p>
                <p class="small grey" id="drawerPhone"></p>
            </div>
        </div>

        <div class="info-container">
            <div class="block-container">
                <h1 class="small-title">FACULTY ACADEMIC INFO</h1>
                <p class="bold" id="drawerFaculty"></p>
            </div>
            <div class="block-container">
                <h1 class="small-title">DEPARTMENT ASSIGNMENT</h1>
                <p class="bold">Department Unit: <span id="drawerDept" style="font-weight: normal; color: #718096;"></span></p>
            </div>
        </div>
    </section>
</main>