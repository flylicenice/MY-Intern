$(document).ready(function () {
    linkActive();
    printDoc();
    handleELogUpload();
    triggerDropDownMenu();
    loadingAnimation();
    showJobDetailsPanel();
    closeJobDetailsPanel();
    redirectUser();
    drawCharts();
    updateProfile();
    acceptOffer();
    handleProfilePicPreview();
    jobSearch();
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
    $(document).on("click", ".job-posting-card", function (e) {
        if ($(e.target).hasClass('apply-now-btn')) return;

        const title = $(this).find('.job-posting-title').text();
        const company = $(this).find('.company-name-text').text();
        const location = $(this).find('.job-location-text').text();
        const allowance = $(this).find('.job-salary-text').text();
        const desc = $(this).find(".job-desc-text").data('desc');
        const jobId = $(this).find('.apply-now-btn').data('job-id');

        $('#panel-title').text(title);
        $('#panel-company').text(company);
        $('#panel-location').text(location);
        $('#panel-allowance').text(allowance);
        $("#panel-desc").html(desc);
        $('#panel-apply-btn').attr('data-job-id', jobId);

        $("#detailsPanel").show();
        $(".job-panel-overlay").css("display", "flex");
    });

    $("#panel-apply-btn").on('click', function (e) {
        e.preventDefault();
        const job_Id = $(this).attr('data-job-id');
        const hasResume = $(this).attr('data-has-resume');

        if (hasResume == 0) {
            alert("Please attached a resume in your profile.");
            return;
        }

        console.log("Attempting to apply for ID:", job_Id);

        $.ajax({
            url: "/MYIntern/includes/student_apply_process.php",
            type: "POST",
            data: { job_id: job_Id },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert("Success: " + response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed: ", error);
            }
        });
    });
}

function closeJobDetailsPanel() {
    $("#closeDetailsBtn").on("click", function () {
        $(".job-details-panel").css("display", "none");
        $(".job-panel-overlay").css("display", "none");
    })
}

function redirectUser() {
    $('#btn-redirect').on("click", function () {
        window.location.href = "pages/login.php";
    });
}

function drawCharts() {
    const studentApplicationChart = $("#studentApplicationChart")[0];

    $.ajax({
        url: "/MYIntern/includes/get_student_application_data.php",
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                var pending = response.data.pending;
                var viewed = response.data.viewed;
                var offered = response.data.offered;
                if (studentApplicationChart) {
                    new Chart(studentApplicationChart, {
                        type: "bar",
                        data: {
                            labels: ['Pending', 'Viewed', 'Offered'],
                            datasets: [{
                                label: 'Applied Jobs',
                                data: [pending, viewed, offered],
                                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)'],
                                borderColor: ['rgba(255, 99, 132)', 'rgba(255, 159, 64)', 'rgba(255, 205, 86)'],
                                borderWidth: 1
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                }
            }
        },
        error: function (xhr, status, error) {
            console.log("Cannot load charts.");
        }
    });
}

function updateProfile() {
    $("#studentProfileForm").on("submit", function (e) {
        e.preventDefault();

        var formElement = this;
        var profileData = new FormData(formElement);

        if (confirm("Are you sure you want to update the info?")) {
            $.ajax({
                url: '../includes/update_profile_process.php',
                type: "POST",
                dataType: "json",
                data: profileData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === "success") {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("System Error: Failed to transmit application approval request.");
                }
            });
        }
    });
}

function handleProfilePicPreview() {
    $("#profilePicInput").on("change", function () {
        const file = this.files[0];
        if (file) {
            // 1. Client-side MIME validation matching backend boundaries
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert("⚠️ Invalid file format. Please upload a JPG, JPEG, or PNG image template.");
                this.value = ''; // Flush selection allocation
                return;
            }

            // 2. Client-side size validation tracking (2MB max allocation ceiling)
            if (file.size > 2 * 1024 * 1024) {
                alert("⚠️ Picture limit exceeded. Max 2MB image profiles allowed.");
                this.value = '';
                return;
            }

            // 3. Convert image stream to update layout element instantly
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#avatar-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
}

function acceptOffer() {
    $("#acceptOfferBtn").on("click", function (e) {
        e.preventDefault();

        const nearestRow = $(this).closest(".clickable-row");
        const jobId = nearestRow.data("id");
        if (confirm("Do you want to accept this offer?")) {

            $.ajax({
                url: "../../includes/accept_offer_process.php",
                type: "POST", // Best practice for mutation/state updates
                data: {
                    job_id: jobId
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert("Offer accepted successfully!");
                        window.location.reload(); // Refresh to update statuses and badges
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("An unexpected error occurred communicating with the server.");
                }
            });
        }
    });
}

function jobSearch() {
    $('#job-search').on('keyup search change', function() {
        // Grab the search term and convert to lowercase for case-insensitive matching
        var searchTerm = $(this).val().toLowerCase().trim();

        // Loop through each job posting card
        $('.job-posting-card').each(function() {
            var card = $(this);
            
            // Extract the searchable text fragments inside the card
            var title = card.find('.job-posting-title').text().toLowerCase();
            var company = card.find('.company-name-text').text().toLowerCase();
            var location = card.find('.job-location-text').text().toLowerCase();
            
            // Optional: Include the hidden job description attribute inside the search context
            var description = card.find('.job-desc-text').data('desc') ? card.find('.job-desc-text').data('desc').toLowerCase() : '';

            // Check if the search term matches any part of the title, company, location, or description
            if (title.includes(searchTerm) || 
                company.includes(searchTerm) || 
                location.includes(searchTerm) || 
                description.includes(searchTerm)) {
                
                // Show the card if it matches
                card.show();
            } else {
                // Hide the card if it doesn't match
                card.hide();
            }
        });

        // Optional: Display a fallback message if all job cards are hidden
        var visibleCards = $('.job-posting-card:visible').length;
        $('#no-results-msg').remove(); // Clear previous fallback messages if any exist
        
        if (visibleCards === 0) {
            $('#job-posting-area').append('<p id="no-results-msg" style="text-align: center; color: #64748b; margin-top: 20px;">No jobs match your search criteria.</p>');
        }
    });
}