<?php

require_once("../../includes/db.php");

try {
    $selectAdminQuery = "SELECT * FROM user u INNER JOIN admin a ON u.user_id = a.user_id";
    $result = $conn->query($selectAdminQuery);
} catch (Exception $e) {
    header("Location: error.php?error=" . $e->getMessage());
    exit();
}

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
                <button class="action-btn btn-add" onclick="openAddAdminWindow()" style="background-color: #1e3a8a; color: white; border: none;">
                    <i class='bx bx-plus'></i> Add Admin
                </button>
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
                            <th>USER ID</th>
                            <th>EMAIL</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0) : ?>
                            <?php while ($admin = $result->fetch_assoc()): ?>
                                <tr class="lecturer-data-row" data-user-id="<?php echo $admin['user_id']; ?>">
                                    <td><?php echo $admin['admin_id']; ?></td>
                                    <td class="lecturer-name"><?php echo $admin['staff_id']; ?></td>
                                    <td> <?php echo $admin['user_id']; ?> </td>
                                    <td> <?php echo $admin['email']; ?></td>
                                    <td style="padding: 12px;">
                                        <button class="action-btn btn-delete">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="null-state-row">
                                <td colspan="5">No Admin for now</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>