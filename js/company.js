$(document).ready(function () {
    linkActive();
    printDoc();
    addJobPosting();
    loadingAnimation();
    showJobIndicator();
    JobPostingFilter();
    editCompanyInfo();
    validateInfo();
    profilePicUpdate();
    drawCharts();
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

function showJobWindow(show) {
    if (show) {
        $('#addJobForm').css("display", "flex");
    } else {
        $('#addJobForm').css("display", "none");
    }
}

function addJobPosting() {
    $("#jobPostingForm").on("submit", function (event) {
        event.preventDefault();

        var jobPostingData = $(this).serialize();

        $.ajax({
            url: "/MYIntern/includes/add_job_post_process.php",
            type: "POST",
            dataType: "json",
            data: jobPostingData,
            success: function (response) {
                if (response.status === "success") {
                    $("#publishBtn").css("backgroundColor", "green").text("Publish Successfully!");
                    $("#publishBtn").prop("disabled", true);
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                alert("System Error: Could not process request.");
            }
        });
    });
}

function closeJobPosting(buttonElement, jobId) {
    var targetRow = $(buttonElement).closest('.company-job-row');
    var btn = $(buttonElement);

    if (confirm("Are you sure you want to close this posting?")) {

        btn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: "/MYIntern/includes/close_job_posting_process.php",
            type: "POST",
            data: { job_id: jobId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    targetRow.attr('data-status', 'CLOSED');
                    window.location.reload();
                } else {
                    alert("Error: " + response.message);
                    btn.prop('disabled', false).text('Verify');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error details:", error);
                alert("Could not complete the verification request due to network connection failure.");
                btn.prop('disabled', false).text('Verify');
            }
        });
    }
}

function showJobIndicator() {
    $('#job_filter').on('change', function () {
        var selectedText = $(this).find('option:selected').text();
        $("#job-indicator").text(selectedText);
    });
}

function approveApplication(buttonElement, applicationId, matricNo) {
    var btn = $(buttonElement);

    if (confirm("Are you sure you want to approve this candidate?")) {
        btn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: '/MYIntern/includes/offer_application_process.php',
            type: 'POST',
            data: {
                app_id: applicationId,
                matric_no: matricNo
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    btn.prop('disabled', true).text("Offerd");

                } else {
                    alert(response.message);
                    btn.prop('disabled', false).css('opacity', '1');
                }
            },
            error: function () {
                alert("System Error: Failed to transmit application approval request.");
                btn.prop('disabled', false).css('opacity', '1');
            }
        });
    }
}

function JobPostingFilter() {
    $('#job_filter').on('change', function () {
        var selectedJobId = $(this).val();
        var selectedJobText = $(this).find('option:selected').text();

        if (selectedJobId === "ALL") {
            $("#job-indicator").text("ALL");
        } else {
            $("#job-indicator").text(selectedJobText);
        }

        $('.custom-dashboard-table tbody tr').each(function () {
            var rowJobId = $(this).attr('data-job-id');
            if (selectedJobId === "ALL" || rowJobId == selectedJobId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
}

function editCompanyInfo() {
    $("#companyProfileForm").on('submit', function (e) {
        e.preventDefault();

        var formElement = this;
        var formData = new FormData(formElement);
        if (confirm("Are you sure you want to update the info?")) {
            $.ajax({
                url: "/MYIntern/includes/edit_company_info_process.php",
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status == "success") {
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

function validateInfo() {
    $("#street").on("input", function () {
        var length = $.trim($(this).val()).length;

        if (length === 0) {
            $(this).css("border-color", "red");

        } else {
            $(this).css("border-color", "");
        }
    });
}

function profilePicUpdate() {
    $("#profilePicInput").on("change", function () {
        var file = this.files[0];

        if (file) {
            var validTypes = ["image/jpeg", "image/jpg", "image/png"];
            if (!validTypes.includes(file.type)) {
                alert("Invalid picture format. Please use a JPG, JPEG, or PNG image.");
                this.value = "";
                return false;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert("Image is too heavy. Maximum allowed size is 2MB.");
                this.value = "";
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $("#avatarPreview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
}

function drawCharts() {
    const overviewChart = $("#overviewChart")[0];
    const placementChart = $("#placementChart")[0];
    const reviewChart = $("#companyReviewChart")[0];

    if (overviewChart) {
        $.ajax({
            url: '/MYIntern/includes/get_company_application_data.php',
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    new Chart(overviewChart, {
                        type: "bar",
                        data: {
                            labels: response.data.labels,
                            datasets: [{
                                label: 'Applications Received',
                                data: response.data.counts,
                                backgroundColor: "#111E4B",
                                borderRadius: 4
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1 } }
                            }
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed: ", error);
            }
        });
    }

    if (placementChart) {
        $.ajax({
            url: "/MYIntern/includes/get_application_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var pending = response.data.pending ?? 0;
                    var viewed = response.data.viewed ?? 0;
                    var offered = response.data.offered ?? 0;
                    var approved = response.data.approved ?? 0;
                    var rejected = response.data.rejected ?? 0;

                    new Chart(placementChart, {
                        type: "doughnut",
                        data: {
                            labels: ['Pending', 'Viewed', 'Offered', 'Approved', 'Rejected'],
                            datasets: [{
                                label: 'Total Application Status',
                                data: [pending, viewed, offered, approved, rejected],
                                backgroundColor: [
                                    '#F59E0B',
                                    '#3B82F6',
                                    '#111E4B',
                                    '#10B981',
                                    '#EF4444'
                                ],
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        boxWidth: 12,
                                        font: { family: "'Inter', sans-serif", size: 12 }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed: ", error);
            }
        });
    }

    if (reviewChart) {
        $.ajax({
            url: "/MYIntern/includes/get_review_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    new Chart(reviewChart, {
                        type: "bar",
                        data: {
                            labels: ['5 stars', '4 stars', '3 stars', '2 stars', '1 stars'],
                            datasets: [{
                                label: 'Reviews',
                                data: response.data.counts,
                                backgroundColor: ['#10B981',
                                    '#3B82F6', 
                                    '#F59E0B', 
                                    '#F97316', 
                                    '#EF4444',
                                ],
                                borderRadius: 4
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1 } }
                            }
                        }
                    });
                }
            }, 
            error: function (xhr, status, error) {
                console.error("Failed: ", error);
            }
        });
    }
}