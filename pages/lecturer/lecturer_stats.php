<div class="dashboard-header-section" style="margin-bottom: 2rem;">
    <h2 class="table-title">All Students</h2>
    <p class="total-counter-subtitle" style="color: #64748b; font-size: 14px; margin-top: 4px;">
        Total Students: <strong><?php echo $total_students; ?> Students</strong>
    </p>
</div>

<!-- Card grouping: wrap your chart, filters, and table together -->
<div class="content-card" style="background: #fff; padding: 24px; border-radius: 8px; border: 1px solid #e2e8f0;">
    
    <h3 style="font-size: 16px; margin-bottom: 1rem; color: #1e293b;">Application Status Overview</h3>
    <div style="width: 100%; height: 260px; position: relative; margin-bottom: 2rem;">
        <canvas id="statusDoughnutChart"></canvas> 
    </div>


</div>

<div class="filter-buttons-container" style="margin: 1.5rem 0; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; width: 100%;">
    <button class="filter-btn active" data-filter="all" style="background-color: #0f172a; color: #fff; border: 1px solid #0f172a; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.2s;">All</button>
    <button class="filter-btn" data-filter="placed" style="background-color: #fff; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.2s;">Placed (<?php echo $status_counts['Placed']; ?>)</button>
    <button class="filter-btn" data-filter="still-applying" style="background-color: #fff; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.2s;">Still Applying (<?php echo $status_counts['Still Applying']; ?>)</button>
    <button class="filter-btn" data-filter="not-applying" style="background-color: #fff; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.2s;">Not Applying (<?php echo $status_counts['Not Applying']; ?>)</button>
</div>

<div class="search-container" style="margin-bottom: 1.5rem; width: 100%;">
    <input type="text" id="studentSearchInput" placeholder="Search by student name, matric ID, or course..." style="width: 100%; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
</div>

<div class="table-responsive" style="background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; overflow-x: auto; width: 100%; box-sizing: border-box;">
    <table class="lecturer-students-table" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid #edf2f7; color: #4a5568; font-size: 13px; text-transform: uppercase; background: #f8fafc;">
                <th style="padding: 14px 12px;">Matric ID</th>
                <th style="padding: 14px 12px;">Student Name</th>
                <th style="padding: 14px 12px;">Course</th>
                <th style="padding: 14px 12px;">Placed Company</th>
                <th style="padding: 14px 12px;">Status</th>
                <th style="padding: 14px 12px; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($table_result && $table_result->num_rows > 0): 
                while ($row = $table_result->fetch_assoc()): 
                    
                    $row_status = strtolower(trim($row['intern_status'])); 
                    if ($row_status === 'inactive' || $row_status === 'not applying') { 
                        $row_status = 'not-applying'; 
                    }
                    if ($row_status === 'active' || $row_status === 'placed') { 
                        $row_status = 'placed'; 
                    }
                    if ($row_status === 'still applying') { 
                        $row_status = 'still-applying'; 
                    }
            ?>
                <tr class="student-row" data-status="<?php echo $row_status; ?>" style="border-bottom: 1px solid #edf2f7;">
                    <td class="font-bold" style="padding: 16px 12px; font-weight: 700;"><?php echo htmlspecialchars($row['matric_number']); ?></td>
                    <td class="student-name-cell" style="padding: 16px 12px; font-weight: 500; text-transform: capitalize;"><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td style="padding: 16px 12px; color: #64748b;"><?php echo htmlspecialchars($row['course']); ?></td>
                    <td style="padding: 16px 12px;"><?php echo htmlspecialchars($row['placement_details']); ?></td>
                    
                    <td>
                        <?php 
                        if ($row_status === 'placed') {
                            $bg_color = '#e2f0d9'; $text_color = '#385723'; $display_text = 'PLACED';
                        } elseif ($row_status === 'still-applying') {
                            $bg_color = '#fff2cc'; $text_color = '#7f6000'; $display_text = 'STILL APPLYING';
                        } else {
                            $bg_color = '#fce4d6'; $text_color = '#c65911'; $display_text = 'NOT APPLYING';
                        }
                        ?>
                        <span style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; font-weight: bold; padding: 6px 14px; border-radius: 4px; display: inline-block; font-size: 11px;">
                            <?php echo $display_text; ?>
                        </span>
                    </td>

                    <td style="text-align: right; padding: 16px 12px;">
                        <?php if ($row_status === 'not-applying'): ?>
                            <!-- Changed to a button for AJAX -->
<button type="button" 
        class="send-alert-btn" 
        data-id="<?php echo htmlspecialchars($row['matric_number']); ?>"
        style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
    Send Alert Email
</button>
                        <?php else: ?>
                            <a href="view_student_profile.php?student_id=<?php echo urlencode($row['matric_number']); ?>" style="background-color: #2dbfa4; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: 500; display: inline-block; font-size: 12px;">
                                View Profile
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                endwhile; 
            else: 
            ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">No student records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Initialise the Doughnut Chart Layout
    const ctx = document.getElementById('statusDoughnutChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Placed', 'Still Applying', 'Not Applying'],
                datasets: [{
                    data: [
                        <?php echo (int)$status_counts['Placed']; ?>, 
                        <?php echo (int)$status_counts['Still Applying']; ?>, 
                        <?php echo (int)$status_counts['Not Applying']; ?>
                    ],
                    backgroundColor: ['#2dbfa4', '#fff2cc', '#e05638'],
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { family: 'Inter, sans-serif', size: 13 },
                            color: '#334155'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Setup Filter Buttons & Instant Search Bar Logic Components
    const filterButtons = document.querySelectorAll(".filter-btn");
    const searchInput = document.getElementById("studentSearchInput");
    const tableRows = document.querySelectorAll(".student-row");
    let currentFilter = "all";

    function filterTable() {
        const searchText = searchInput.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const studentStatus = row.getAttribute("data-status");
            const rowText = row.textContent.toLowerCase();

            // Check if row matches current active filter button
            const matchesFilter = (currentFilter === "all" || studentStatus === currentFilter);
            // Check if row matches typed search string field keywords
            const matchesSearch = rowText.includes(searchText);

            if (matchesFilter && matchesSearch) {
                row.style.display = ""; 
            } else {
                row.style.display = "none"; 
            }
        });
    }
    document.querySelectorAll(".send-alert-btn").forEach(button => {
        button.addEventListener("click", function() {
            const matricNumber = this.getAttribute("data-id");
            const originalText = this.innerText;

            this.innerText = "Sending...";
            this.disabled = true;

            fetch('send_alert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'matric_number=' + encodeURIComponent(matricNumber)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    this.innerText = "Sent";
                    this.style.backgroundColor = "#2dbfa4";
                } else {
                    alert("Error: " + data.message);
                    this.innerText = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to send email.");
                this.innerText = originalText;
                this.disabled = false;
            });
        });
    });

    // Assign click logic to tab buttons
    filterButtons.forEach(button => {
        button.addEventListener("click", function() {
            filterButtons.forEach(btn => {
                btn.style.backgroundColor = "#fff";
                btn.style.color = "#334155";
                btn.style.borderColor = "#cbd5e1";
            });
            
            this.style.backgroundColor = "#0f172a";
            this.style.color = "#fff";
            this.style.borderColor = "#0f172a";

            currentFilter = this.getAttribute("data-filter");
            filterTable();
        });
    });

    // Assign keyup logic for typing into search field
    if (searchInput) {
        searchInput.addEventListener("keyup", filterTable);
    }
});
</script>