$(document).ready(function() {
    getJobDetails();
});

function getJobDetails() {
    const jobCard = $(".job-posting-card");

    if (jobCard) {
        $.ajax({
            url: 'get_job-details.php',
            type: "GET",
            data: { id: "1" },
            
        }); 
    }
}