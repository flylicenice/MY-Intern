$(document).ready(function () {
    const assignedChartCanvas = document.getElementById('assignedInternsChart')[0];

    if (assignedChartCanvas) {
        $.ajax({
            url: "/MYIntern/includes/get_chart_data.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    console.log("h");
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
                                    '#1e295d', // Still Applying (Dark Blue)
                                    '#c2d468', // Not Applying (Yellow-Green)
                                    '#b2bec3'  // Placed (Silver Gray)
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
};

});
