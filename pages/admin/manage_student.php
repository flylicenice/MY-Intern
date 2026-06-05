<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Students</h1>
            <p>Total students: 350 students</p>
        </div>
    </div>

    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>Total Registered Students</h3>
            </div>
            <div class="chart">
                <canvas id="allStudentsChart"></canvas>
            </div>
        </div>
    </section>

    <section class="data-table-section">
        <div class="top-bar">
            <input type="text" placeholder="Search by name, matric no...">
        </div>

        <div class="absolute-relative-container">
            <div class="btn-container top-bar">
                <button class="action-btn" id="filter-btn">Filter</button>
            </div>

            <div class="drop-down-container" id="filter-container">
                <div class="option-container">
                    <input id="active-rb" type="radio" value="active" name="filter">
                    <label for="active-rb">Active</label>
                </div>

                <div class="option-container">
                    <input id="pending-rb" type="radio" value="pending" name="filter">
                    <label for="active-rb">Pending</label>
                </div>
            </div>
        </div>

        <h2 class="table-title">Student Details</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Matric No.</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="null-row" colspan="5">
                            No student for now
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <?php echo "Arissa Balqis binti Amir Amran"; ?>
                        </td>
                        <td>
                            <?php echo "D032410001"; ?>
                        </td>
                        <td>
                            <?php echo "NOR HASLINDA BINTI ISMAIL"; ?>
                        </td>
                        <td>
                            <p class="status-badge pending">PENDING</p>
                        </td>
                        <td>
                            <button id="approve-btn" class="action-btn btn-approve">Approve</button>
                            <button class="action-btn btn-view">View</button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <?php echo "Siti Safiyyah Tay binti Muhammad Daniel Tay"; ?>
                        </td>
                        <td>
                            <?php echo "D032410002"; ?>
                        </td>
                        <td>
                            <?php echo "NOR HASLINDA BINTI ISMAIL"; ?>
                        </td>
                        <td>
                            <p class="status-badge pending">PENDING</p>
                        </td>
                        <td>
                            <button class="action-btn btn-approve">Approve</button>
                            <button class="action-btn btn-view">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="details-section">
        <button class="action-btn" id="closeDetailsBtn" type="button">&times;</button>

        <div class="top-layer-container">
            <div class="profile-container">
                <img src="../../assets/default-user.svg" alt="user-profile" width=96 height=96>
            </div>

            <div class="personal-info-container">
                <p class="bold main">TAM KAI DIT</p>
                <p class="bold grey">D032410113</p>
                <p class="small grey">tamkaidit50@gmail.com</p>
                <p class="small grey">011-31865344</p>
            </div>
        </div>

        <div class="info-container">
            <div class="block-container">
                <h1>ACADEMIC INFO</h1>
                <p class="bold">Course: <?php echo "DCS"; ?></p> 
            </div>

            <div class="block-container">
                <h1>PLACEMENT INFO</h1>
                <p class="bold">Company: <?php echo "Google"; ?></p>
                <p class="bold">Supervisor: <?php echo "NOR HASLINDA BINTI ISMAIL"; ?></p>
            </div>
        </div>
    </section>
</main>