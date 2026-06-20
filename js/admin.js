$(document).ready(function () {
    validateAddAdminForm();
    closeAdminWindow();
    drawCharts();
    togglePassword();
});

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
                    $("#saveAdminBtn").prop("disabled", true);
                } else if (response.status === "success") {
                    $("")
                    $("#adminPasswordError").text(response.message);
                    $("#saveAdminBtn").prop("disabled", true);
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
    const lecturerChart = $('#lecturerChart')[0];
    const companyChart = $('#companyChart')[0];
    const allStudentsChart = $("#allStudentsChart")[0];

    if (studentChart) {
        new Chart(studentChart, {
            type: "line",
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Monthly Student',
                    data: [0, 60, 85, 0, 145, 180, 210, 240, 265, 300, 325, 350],
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
                            stepSize: 100
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    if (lecturerChart) {
        new Chart(lecturerChart, {
            type: "doughnut",
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    label: 'Total Number of Lecturer',
                    data: [45, 60],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    if (companyChart) {
        new Chart(companyChart, {
            type: "doughnut",
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    label: 'Total Number of Lecturer',
                    data: [45, 60],
                    backgroundColor: ['#E2E8F0', '#111E4B']
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    if (allStudentsChart) {
        new Chart(allStudentsChart, {
            type: "doughnut",
            data: {
                labels: ['Hunting', 'Approved For Placement'],
                datasets: [{
                    label: 'Registered Students',
                    data: [100, 200],
                    backgroundColor: ['#E2E8F0', '#111E4B']
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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