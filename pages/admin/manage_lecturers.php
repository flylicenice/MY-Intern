<?php

require_once("../../includes/db.php");

try {
    $selectLecturerQuery = "SELECT * FROM user u INNER JOIN lecturer l ON u.user_id = l.user_id";
    $result = $conn->query($selectLecturerQuery);

    $countQuery = "SELECT COUNT(*) AS total_lecturer FROM lecturer";
    $countResult = $conn->query($countQuery);

    if ($countResult) {
        $countRow = $countResult->fetch_assoc();
        $total_lecturer = $countRow['total_lecturer'];
    }
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}

?>

<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Lecturer</h1>

            <p>Total lecturers : <span id="lecturer-total-count"><?php echo $total_lecturer; ?></span> lecturers</p>
        </div>
    </div>
    <section class="data-table-section">
        <div class="absolute-relative-container">
            <div class="btn-container top-bar">
                <button class="action-btn btn-add" onclick="openAddLecturerWindow()" style="background-color: #1e3a8a; color: white; border: none;">
                    <i class='bx bx-plus'></i> Add Lecturer
                </button>
            </div>
        </div>

        <div class="details-card">
            <h2 class="table-title">Lecturers Details</h2>

            <div class="top-bar">
                <input type="text" id="tableSearchInput" placeholder="Search by name, ID..." onkeyup="filterLecturerTable()">
            </div>

            <div class="table-responsive">
                <table id="lecturerTable">
                    <thead>
                        <tr>
                            <th>LECTURER ID</th>
                            <th>STAFF ID</th>
                            <th>FULL NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE NO.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0) : ?>
                            <?php while ($lecturers = $result->fetch_assoc()): ?>
                                <tr class="lecturer-data-row" data-status="<?php echo $lecturers['status']; ?>">
                                    <td><?php echo $lecturers['staff_id']; ?></td>
                                    <td class="lecturer-name"><?php echo $lecturers['lecturer_id']; ?></td>
                                    <td><?php echo $lecturers['full_name']; ?></td>
                                    <td> <?php echo $lecturers['email']; ?></td>
                                    <td> <?php echo $lecturers['phone_number']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="null-state-row">
                                <td colspan="6">No Lecturer for now</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>