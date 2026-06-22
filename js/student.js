$(document).ready(function () {
    linkActive();
    printDoc();
    handleELogUpload();
    triggerDropDownMenu();
    loadingAnimation();
    showJobDetailsPanel();
});

function loadingAnimation() {
    if (document.readyState === "complete") {
        $(".loader-wrapper").fadeOut("slow");
    } else {
        $(window).on("load", function () {
            $(".loader-wrapper").fadeOut("slow");
        });
    }
}

function triggerDropDownMenu() {
    const profileTrigger = $("#profile-trigger");
    const menu = $("#profile-menu");
    const dropDownArrow = $('#drop-down-profile-arrow');
    const filterBtn = $("#filter-btn");
    const filterMenu = $('#filter-container');

    if (profileTrigger.length && menu.length && dropDownArrow.length) {
        profileTrigger.on("click", function (e) {
            e.stopPropagation();
            menu.toggleClass("show");
            dropDownArrow.toggleClass("show");
        });

        $(document).on("click", function (e) {
            if (!menu.is(e.target) && menu.has(e.target).length === 0 && !profileTrigger.is(e.target)) {
                menu.removeClass("show");
                dropDownArrow.removeClass("show");
            }
        });
    }

    if (filterBtn.length && filterMenu.length) {
        filterBtn.on('click', function (e) {
            e.stopPropagation();
            filterMenu.toggleClass("show");
        });
    }
}

function linkActive() {
    const currentPath = window.location.pathname;
    const currentSearch = window.location.search;
    const navLinks = document.querySelectorAll('.nav-bar a');
    const studentNavLinks = document.querySelectorAll('.white-link');
    const adminNavLinks = document.querySelectorAll('.nav-item');

    navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });

    studentNavLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === currentSearch) {
            link.classList.add("active");
        }
    });

    adminNavLinks.forEach(link => {
        const innerLink = link.querySelector('a');
        if (innerLink) {
            link.classList.remove('active');
            if (innerLink.getAttribute("href") === currentSearch) {
                link.classList.add('active');
            }
        }
    });
}

function printDoc() {
    $("#dashboard-print-trigger").on("click", function () {
        window.print();
    });
}

function handleELogUpload() {
    $(document).on("submit", "#logbookForm", function (event) {
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
                if (response.status === "success") {
                    alert("Logbook submitted successfully!");
                    window.top.location.href = "student_dashboard.php?page=e-log&status=success";
                } else {
                    alert("Upload failed: " + response.message);
                    submitBtn.prop('disabled', false).text('Upload Weekly Logbook');
                }
            },
        });
    });
}

function showJobDetailsPanel() {
    const jobCard = $(".job-posting-card");
    const jobDetailPanel = $(".job-details-panel");

   if (jobCard.length && jobDetailPanel.length) {
          
        jobCard.off("click").on("click", function() {
            jobDetailPanel.show();
        });
    }

    $(document).off("click", "#closeDetailsBtn").on("click", "#closeDetailsBtn", function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        $(".job-details-panel").hide();
    });


    $(document).off("click", ".apply-now-btn").on("click", ".apply-now-btn", function(e) {
        e.preventDefault();
        
        const jobId = $(this).attr('data-jobid') || 1;
        console.log("Submitting application for Job ID: " + jobId);

        fetch('pages/student/student_application.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'job_id=' + encodeURIComponent(jobId)
        })
        .then(response => {
            if (!response.ok) throw new Error('Network query execution error.');
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert('🎉 ' + data.message);
                window.location.href = 'pages/student/student_dashboard.php';
            } else {
                alert('⚠️ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Transmission System Error:', error);
            alert('⚠️ Application transmission failed. Verify your network connection or backend script.');
        });
    });
}