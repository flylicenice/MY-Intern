<?php
include '../../includes/db.php';

// Run combined query for counts using intern_status from student table
$sql = "SELECT 
            (SELECT COUNT(*) FROM student) AS total_students,
            (SELECT COUNT(*) FROM student WHERE intern_status='Placed') AS placed_students,
            (SELECT COUNT(*) FROM student WHERE intern_status='Still Applying') AS applying_students,
            (SELECT COUNT(*) FROM student WHERE intern_status='Not Applying') AS not_applying_students";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

$total = $data['total_students'];
$placed = $data['placed_students'];
$applying = $data['applying_students'];
$not_applying = $data['not_applying_students'];

// Query student list for the table
$students = $conn->query("
    SELECT matric_number, full_name, course, intern_status
    FROM student
");
?>

<main class="dashboard-container">

    <div class="header-row">
        <div>
            <h1>Application Overview</h1>
            <p>Welcome back, <?php echo "Madam" ?></p>
        </div>
    </div>

    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <h3>Total Assigned Interns</h3>
            </div>
            <div class="chart">
            <canvas id="assignedInternsChart">
                data-placed="<?php echo $placed; ?>"
                data-applying="<?php echo $applying; ?>"
                data-notapplying="<?php echo $not_applying; ?>">
            </canvas>
            <script>
            const ctx = document.getElementById("assignedInternsChart").getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ['Placed', 'Still Applying', 'Not Applying'],
                    datasets: [{
                        data: [<?php echo $placed; ?>, <?php echo $applying; ?>, <?php echo $not_applying; ?>],
                        backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                    }]
                }
            });
            </script>
            </div>
        </div>
    </section>

    <section class="data-table-section">
        <div class="table-header-flex">
            <div>
                <h2 class="table-title">All Students</h2>
                <p class="total-counter-subtitle">Total Students: <?php echo $total; ?> Students</p>
            </div>
            
            <div class="status-filter-pills-row">
                <button class="filter-pill active" onclick="filterStatus('ALL')">All (<?php echo $total; ?>)</button>
                <button class="filter-pill" onclick="filterStatus('PLACED')">Placed (<?php echo $placed; ?>)</button>
                <button class="filter-pill" onclick="filterStatus('APPLYING')">Still Applying (<?php echo $applying; ?>)</button>
                <button class="filter-pill danger-pill" onclick="filterStatus('NONE')">Not Applying (<?php echo $not_applying; ?>)</button>
            </div>
        </div>

        <div class="top-bar" style="margin-bottom: 1.25rem;">
            <input type="text" id="studentSearchInput" placeholder="Search by student name, matric number, or course..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="globalStudentTable">
                <thead>
                    <tr>
                        <th>Matric Number</th>
                        <th>Student Name</th>
                        <th>Course / Faculty</th>
                        <th>Status</th>
                        <th style="text-align: right; padding-right: 20px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $students->fetch_assoc()): ?>
                    <tr data-status="<?php echo strtoupper($row['intern_status']); ?>">
                        <td class="font-bold"><?php echo $row['matric_number']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td class="text-muted"><?php echo $row['course']; ?></td>
                        <td>
                            <?php if($row['intern_status'] == 'Placed'): ?>
                                <span class="status-pill-badge status-placed">Placed</span>
                            <?php elseif($row['intern_status'] == 'Still Applying'): ?>
                                <span class="status-pill-badge status-applying">Still Applying</span>
                            <?php else: ?>
                                <span class="status-pill-badge status-none">Not Applying</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right; padding-right: 20px;">
                            <a href="view_student_profile.php?id=<?php echo $row['matric_number']; ?>" class="action-btn btn-view">Profile</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
