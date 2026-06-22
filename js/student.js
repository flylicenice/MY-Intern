$(document).ready(function () {
    linkActive();
    printDoc();
    handleELogUpload();
    handleApplyJob();
    showJobDetailsPanel();
});



function linkActive() {
    const currentPath = window.location.pathname;
    const currentSearch = window.location.search;

    $('.nav-item').each(function () {
        var link = $(this);
        var innerLink = link.find('a');

        if (innerLink.length > 0) {
            link.removeClass('active');

            if (innerLink.attr("href") === currentSearch) {
                link.addClass('active');
            }
        }
    });
}

function printDoc() {
    $("#dashboard-print-trigger").on("click", function () {
        window.print();
    });
}

function handleELogUpload()
{
   $(document).on("submit", "#logbookForm", function(event){
        event.preventDefault(); 

        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');

        submitBtn.prop('disabled', true).text('Uploading...');

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: formData,
            contentType: false, 
            processData: false, 
            success: function (response) {
                if (response.status === "success"){
                  alert("Logbook submitted successfully!");
                  window.top.location.href = "student_dashboard.php?page=e-log&status=success";
            }else{
                  alert("Upload failed: " + response.message);            
                  submitBtn.prop('disabled', false).text('Upload Weekly Logbook');
            }
        },
        });
   });
}

function showJobDetailsPanel() {
    $(document).on("click", ".job-posting-card", function(e) {
        if ($(e.target).hasClass('apply-now-btn')) return;

        const title = $(this).find('.job-posting-title').text();
        const company = $(this).find('.company-name-text').text();
        const location = $(this).find('.job-location-text').text();
        const allowance = $(this).find('.job-salary-text').text();
        const jobId = $(this).find('.apply-now-btn').data('jobid');

        $('#panel-title').text(title);
        $('#panel-company').text(company);
        $('#panel-location').text(location);
        $('#panel-allowance').text(allowance);
        
        $('#panel-apply-btn').attr('data-jobid', jobId); 
        
        $("#detailsPanel").show();
    });

    $(document).off('click', '#panel-apply-btn').on('click', '#panel-apply-btn', function(e) {
        e.preventDefault();
        const jobId = $(this).attr('data-jobid');
        
        console.log("Attempting to apply for ID:", jobId); // CHECK THIS IN CONSOLE!

        $.post('/MYIntern/pages/student/process_apply.php', { job_id: jobId }, function(response) {
            if (response.status === 'success') {
                alert('Success: ' + response.message);
                window.location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function(xhr) {
            console.error(xhr.responseText);
            alert("Server Error! Check Console (F12).");
        });
    });
}

function handleApplyJob() {
    $(document).on('click', '.apply-now-btn', function(e) {
        e.preventDefault();
        const jobId = $(this).data('jobid');
        
        $.ajax({
            url: '/MYIntern/pages/student/process_apply.php',
            type: 'POST',
            data: { job_id: jobId },
            dataType: 'json',
            success: function(data) {
                if(data.status === 'success') { 
                    alert(data.message);
                    location.reload(); 
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function() {
                alert('Application transmission failed.');
            }
        });
    });
}

