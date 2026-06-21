<?php

require_once("../../includes/db.php");

try {
    $selectStudentQuery = "SELECT * FROM user u INNER JOIN student s ON u.user_id = s.user_id";
    $result = $conn->query($selectStudentQuery);

    $countQuery = "SELECT COUNT(*) AS total_student FROM student";
    $countResult = $conn->query($countQuery);

    if ($countResult) {
        $countRow = $countResult->fetch_assoc();
        $total_student = $countRow['total_student'];
    }
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}

?>

<main class="dashboard-container">
    <div class="header-row">
        <div>
            <h1>Manage Students</h1>
            <p>Total students: <?php echo $total_student; ?> </p>
        </div>
    </div>

    <section class="data-table-section">
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

        <div class="top-bar">
            <input type="text" placeholder="Search by name, matric no...">
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NAME</th>
                        <th>MATRIC NO.</th>
                        <th>IDENTIFICATION NO.</th>
                        <th>EMAIL</th>
                        <th>PHONE NO.</th>
                        <th>STATUS</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0) : ?>
                        <?php while ($students = $result->fetch_assoc()): ?>
                            <tr class="student-data-row" data-status="<?php echo $students['status']; ?>" data-user-id="<?php echo $students['user_id']; ?>">
                                <td><?php echo $students['full_name']; ?></td>
                                <td><?php echo $students['matric_number']; ?></td>
                                <td><?php echo $students['identification_no']; ?></td>
                                <td> <?php echo $students['email']; ?></td>
                                <td> <?php echo $students['phone_number']; ?></td>
                                <?php if ($students['status'] === 'pending'): ?>
                                    <td>
                                        <span class="status-badge <?php echo $students['status']; ?>"><?php echo $students["status"]; ?></span>
                                    </td>
                                    <td>
                                        <button class="action-btn btn-verify" onclick="verifyStudent(this, <?php echo $students['user_id']; ?>)">Verify</button>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <span class="status-badge <?php echo $students['status']; ?>"><?php echo $students["status"]; ?></span>
                                    </td>
                                    <td>
                                        <button class="action-btn btn-verify" disabled>Verify</button>
                                    </td>
                                <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr id="null-state-row">
                            <td colspan="6">No Student for now</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>