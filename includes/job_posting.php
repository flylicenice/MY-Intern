<?php
// includes/job_posting.php

// 1. Fetch jobs from the database
$sql = "SELECT id, title, company_name, location, salary, company_logo, created_at 
        FROM job_vacancies 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

// 2. Evaluate if active database entries exist
if ($result && $result->num_rows > 0): 
    while($job = $result->fetch_assoc()): 
        
        // Fallback to a default asset icon if the company hasn't uploaded a logo
        $logoPath = !empty($job['company_logo']) ? $job['company_logo'] : 'assets/default-company.png';
        
        // Calculate relative time statement (e.g., "1 month ago")
        $postedDate = new DateTime($job['created_at']);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($postedDate);
        
        if ($interval->m > 0) {
            $timeAgo = $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
        } elseif ($interval->d > 0) {
            $timeAgo = $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
        } else {
            $timeAgo = "Just now";
        }
?>
    
    <div class="job-posting-card" onclick="window.location.href='view_job.php?id=<?php echo $job['id']; ?>'">
        
        <div class="company-logo-container">
            <img src="<?php echo htmlspecialchars($logoPath); ?>" alt="Company Logo" class="company-logo-img">
        </div>
        
        <div class="job-details-container">
            <h3 class="job-posting-title"><?php echo htmlspecialchars($job['title']); ?></h3>
            <p class="company-name-text"><?php echo htmlspecialchars($job['company_name']); ?></p>
            <p class="job-location-text"><?php echo htmlspecialchars($job['location']); ?></p>
            <p class="job-salary-text">RM <?php echo htmlspecialchars($job['salary']); ?> per month</p>
            <span class="posted-time-badge"><?php echo $timeAgo; ?></span>
        </div>
        
    </div>

<?php 
    endwhile; 
else: 
?>
    <div class="empty-jobs-card">
        <p>No internship vacancies found matching your search profile.</p>
    </div>
<?php 
endif; 
?>