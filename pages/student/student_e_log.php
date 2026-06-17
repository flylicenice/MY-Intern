<section class="data-table-section">
    <h2 class="table-title">Activity Logbook</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th>File</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $week = 1;
                    $submitted = false;
                    while ($week < 10): 
                ?>
                <tr>
                    <td>
                        <?php echo $week ?>
                    </td>
                    <td>
                        <?php 
                        if ($submitted) {
                            echo 'week1_report.pdf';
                            $submitted = false;
                        } else if (!$submitted) {
                            echo "No Logbook has been submitted";
                            $submitted = true;
                        }
                        ?>
                    </td>
                    <?php if($week % 2 == 0): ?> 
                    <td>
                        <p class="status-badge active">Submitted</p>
                    </td>
                    <td>
                        <a href="upload_logbook.php" target="_blank"><button class="action-btn">View</button></a>
                    </td>
                    <?php else: ?>
                    <td>
                        <p class="status-badge pending">Pending</p>
                    </td>
                    <td>
                        <a href="upload_logbook.php" target="_blank"><button class="action-btn btn-view">Upload</button></a>
                    </td>
                    <?php endif; ?>
                    
                </tr>
                <?php 
                    $week++;
                    endwhile;
                ?>
            </tbody>
        </table>
    </div>
</section>
