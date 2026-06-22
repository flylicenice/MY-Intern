$(document).ready(function () {
    const assignedChartCanvas = document.getElementById('assignedInternsChart');

    if (assignedChartCanvas) {
        $.ajax({
            url: "../../includes/get_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    const placedCount = response.data.placed;
                    const applyingCount = response.data.applying;
                    const notApplyingCount = response.data.not_applying;
                    new Chart(assignedChartCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: ['Still Applying', 'Not Applying', 'Placed'],
                            datasets: [{
                                label: 'Intern Status',
                                data: [applyingCount, notApplyingCount, placedCount],
                                backgroundColor: [
                        '#FFC107', // Still Applying -> Deep, High-Contrast Amber Yellow
                        '#D32F2F', // Not Applying   -> Harsh Crimson Warning Red!
                        '#2E7D32'  // Placed         -> Deep, Solid Dark Green
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 20
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed to load chart data: ", error);
            }
        });
    }

    window.filterStatus = function (statusKey) {

        $('.filter-pill').removeClass('active');

        const targetPill = $(`.filter-pill[onclick*="'${statusKey}'"]`);
        if (targetPill.length) {
            targetPill.addClass('active');
        }

        $('#globalStudentTable tbody tr').each(function () {
            if (statusKey === 'ALL') {
                $(this).show();
                return;
            }

            const rowStatus = $(this).attr('data-status');
            if (rowStatus === statusKey) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    };

    window.searchTable = function () {
        const value = $('#tableSearchInput').val().toLowerCase();
        
        $('#globalStudentTable tbody tr').each(function () {
            const toggleRow = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(toggleRow);
        });
    };
    window.searchTable = function() {

    const searchVal = $('#studentSearchInput').val().toLowerCase();

    $('#globalStudentTable tbody tr').each(function() {
        // Skip the "No records found" row if it's showing
        if ($(this).find('td').length === 1) return;

        const rowText = $(this).text().toLowerCase();

        if (rowText.indexOf(searchVal) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    $(document).on('click', '.send-alert-btn', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const matricNumber = button.data('id');
        const originalText = button.text();

        // Visual feedback to the user
        button.text('Sending...').prop('disabled', true);

        $.ajax({
            url: 'send_alert.php',
            type: 'POST',
            data: { matric_number: matricNumber },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    button.text('Sent').css('background-color', '#2dbfa4');
                } else {
                    alert("Error: " + response.message);
                    button.text(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Failed to connect to the server. Please check your network.");
                button.text(originalText).prop('disabled', false);
            }
        });
    });
};

});
