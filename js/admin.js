$(document).ready(function () {
    linkActive();
    validateAddAdminForm();
    deleteAdmin();
    closeAdminWindow();
    drawCharts();
    togglePassword();
    addLecturerForm();
    closeLecWindow();
    filterandSearch();
    printDoc();
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

function validateAddAdminForm() {
    $("#admin_password").on('input', function () {
        var passwordLength = $(this).val().length;

        if (passwordLength < 8) {
            $("#adminPasswordError").css("display", "inline");
            $("#adminPasswordError").text("Password must be at least 8 characters long.");
            $("#saveAdminBtn").prop("disabled", true);
        } else {
            $("#adminPasswordError").text("");
        }
    });

    $("#addAdminForm").on("submit", function (event) {
        event.preventDefault();

        var adminFormData = $(this).serialize();

        $.ajax({
            url: "/MYIntern/includes/add_admin_process.php",
            type: "POST",
            data: adminFormData,
            dataType: "json",
            success: function (response) {
                if (response.status === "error") {
                    $("#adminPasswordError").css("display", "inline");
                    $("#adminPasswordError").text(response.message);
                } else if (response.status === "success") {
                    $("#adminPasswordError").text("");
                    $("#saveAdminBtn").text(response.message);
                    $("#saveAdminBtn").prop("disabled", true);
                    window.opener.location.reload();
                }
            },
            error: function (xhr, status, error) {
                $("#saveAdminBtn").prop("disabled", true);
            },
        });
    });
}

function closeAdminWindow() {
    $("#closeAdminWindow").on("click", function () {
        window.close();
    });
}

function drawCharts() {
    const studentChart = $("#studentChart")[0];
    const placementChart = $('#placementChart')[0];
    const companyChart = $('#companyChart')[0];

    if (studentChart) {
        $.ajax({
            url: "/MYIntern/includes/get_student_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var labels = response.labels;
                    var studentCounts = response.data;

                    new Chart(studentChart, {
                        type: "line",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Monthly Student Registrations',
                                data: studentCounts,
                                borderColor: "#111E4B",
                                backgroundColor: "transparent",
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            }],
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    },
                                    ticks: {
                                        font: { size: 10 }
                                    }
                                },
                                y: {
                                    grid: {
                                        display: true
                                    },
                                    ticks: {
                                        font: { size: 10 },
                                        stepSize: 1
                                    },
                                    border: {
                                        display: false
                                    }
                                }
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

    if (companyChart) {
        $.ajax({
            url: "/MYIntern/includes/get_company_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var verifiedCount = response.data.verified;
                    var unverifiedCount = response.data.unverified;
                    new Chart(companyChart, {
                        type: "doughnut",
                        data: {
                            labels: ['Verified', 'Unverified'],
                            datasets: [{
                                label: 'Company Verification',
                                data: [verifiedCount, unverifiedCount],
                                backgroundColor: ['#E2E8F0', '#111E4B']
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
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
            url: "/MYIntern/includes/get_placement_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                var placedCount = response.data.placed;
                var completedCount = response.data.completed;

                if (response.status === "success") {
                    new Chart(placementChart, {
                        type: "doughnut",
                        data: {
                            labels: ['Completed', 'Placed'],
                            datasets: [{
                                label: 'Placement Status',
                                data: [completedCount, placedCount],
                                backgroundColor: ['#E2E8F0', '#111E4B']
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            },
            error: function (xhs, status, error) {
                console.error("Failed: ", error);
            }
        });
    }
}

function togglePassword() {
    $("#togglePassword").on("click", function () {
        var FieldType = $("#password").attr('type');
        if (FieldType === 'password') {
            $("#password").attr('type', 'text');
        } else {
            $("#password").attr('type', 'password');
        }
    });
}

function deleteAdmin() {
    $(document).on("click", ".btn-delete", function () {
        var clickedRow = $(this).closest("tr");
        var userId = clickedRow.data("user-id");
        var staffId = clickedRow.find(".lecturer-name").text();

        if (!userId) {
            alert("Error: Could not find valid user id here.");
            return;
        }

        if (confirm("CRITICAL: Are you completely sure you want to permanently delete Admin (" + staffId + ")?")) {

            $.ajax({
                url: "/MYIntern/includes/delete_admin_process.php",
                type: "POST",
                data: { user_id: userId },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        alert(response.message);
                        window.location.reload();
                    } else if (response.status === "failed") {
                        alert("Error: " + response.message);
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error stream detail:", error);
                    alert("Could not complete deletion synchronization request.");
                }
            });
        }
    });
}

function toggleCompanyFilterMenu() {
    const container = document.getElementById('company-filter-container');
    container.style.display = (container.style.display === 'none' || container.style.display === '') ? 'block' : 'none';
}

function filterCompanyTable() {
    const searchFieldQuery = document.getElementById('tableSearchInput').value.toLowerCase();
    const checkedRadioOption = document.querySelector('input[name="filter"]:checked');
    const statusConstraint = checkedRadioOption ? checkedRadioOption.value : 'all';

    const operationalRows = document.querySelectorAll('.company-data-row');
    let visibleMatchCounter = 0;

    operationalRows.forEach(row => {
        if (row.id === 'null-state-row') return;

        const companyId = row.cells[0].innerText.toLowerCase();
        const companyName = row.cells[1].innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');

        const matchesStatus = (statusConstraint === 'all' || rowStatus === statusConstraint);
        const matchesSearch = (companyId.includes(searchFieldQuery) || companyName.includes(searchFieldQuery));

        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleMatchCounter++;
        } else {
            row.style.display = 'none';
        }
    });

    const nullRow = document.getElementById('null-state-row');
    if (nullRow) {
        nullRow.style.display = (visibleMatchCounter === 0) ? '' : 'none';
    }
    document.getElementById('company-total-count').innerText = visibleMatchCounter;
}

function verifyCompany(buttonElement, companyId) {
    var targetRow = $(buttonElement).closest('.company-data-row');
    var btn = $(buttonElement);

    if (confirm("Are you sure you want to verify this company?")) {

        // Disable button instantly to prevent double-clicking
        btn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: "/MYIntern/includes/verify_company_process.php",
            type: "POST",
            data: { company_id: companyId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    targetRow.attr('data-status', 'verified');
                    window.location.reload();

                    btn.text('Verify');
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

function verifyStudent(buttonElement, userId) {
    var targetRow = $(buttonElement).closest('.student-data-row');
    var btn = $(buttonElement);

    if (confirm("Are you sure you want to verify this student?")) {

        // Disable button instantly to prevent double-clicking
        btn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: "/MYIntern/includes/verify_student_process.php",
            type: "POST",
            data: { user_id: userId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    window.location.reload();

                    btn.text('Verify');
                } else {
                    alert("Error: " + response.message);
                    btn.prop('disabled', false).text('Verify');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error details:", error);
                alert("Could not complete the verification request.");
                btn.prop('disabled', false).text('Verify');
            }
        });
    }
}

function addLecturerForm() {
    $("#addLecturerForm").on("submit", function (event) {
        event.preventDefault();

        var passwordLength = $("#lec_password").val().length;
        const icRegex = /^(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-*[0-9]{2}-*[0-9]{4}$/;

        $("#lec_password").css("border-color", "");
        $("#lec_ic").css("border-color", "");

        if (passwordLength < 8) {
            alert("Password must be 8 characters long.");
            $("#lec_password").css("border-color", "red");
        } else if (!icRegex.test($("#lec_ic").val())) {
            alert("Please enter the correct IC format.");
            $("#lec_ic").css("border-color", "red");
        } else {
            var lecFormData = $(this).serialize();

            $.ajax({
                url: "/MYIntern/includes/add_lecturer_process.php",
                type: "POST",
                data: lecFormData,
                dataType: "json",
                success: function (response) {
                    if (response.status === "error") {
                        alert(response.message);
                    } else if (response.status === "success") {
                        alert(response.message);
                        window.opener.location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    alert("Server Error");
                },
            });
        }
    });
}

function closeLecWindow() {
    $("#closeLecWindow").on("click", function () {
        window.close();
    });
}

function filterAndSearchStudents() {
    var searchVal = $(".top-bar input[type='text']").val().toLowerCase().trim();

    var activeFilter = $("input[name='filter']:checked").val();

    var visibleCount = 0;

    $(".student-data-row").each(function () {
        var row = $(this);

        var studentName = row.find("td:nth-child(1)").text().toLowerCase();
        var matricNo = row.find("td:nth-child(2)").text().toLowerCase();
        var rowStatus = row.attr("data-status");

        var matchesSearch = studentName.includes(searchVal) || matricNo.includes(searchVal);

        var matchesFilter = (!activeFilter || rowStatus === activeFilter);

        if (matchesSearch && matchesFilter) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });

    if (visibleCount === 0) {
        if ($("#no-match-row").length === 0) {
            $("table tbody").append(
                '<tr id="no-match-row"><td colspan="7" style="text-align:center; padding: 20px; color:#a0aec0;">No matching records found.</td></tr>'
            );
        }
        $("#null-state-row").hide();
    } else {
        $("#no-match-row").remove();
    }
}

function filterandSearch() {
    $(".top-bar input[type='text']").on("input", filterAndSearchStudents);
    $("input[name='filter']").on("change", filterAndSearchStudents);
}

function printDoc() {
    $("#dashboard-print-trigger").on("click", function() {
        window.print();
    });
}