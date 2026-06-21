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
                                    '#1e295d', // Still Applying (Dark Blue)
                                    '#c2d468', // Not Applying (Yellow-Green)
                                    '#b2bec3'  // Placed (Silver Gray)
                                ],
                                borderWidth: 2
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
});