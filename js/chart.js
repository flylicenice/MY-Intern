$(document).ready(function () {
    drawCharts();
});

function drawCharts() 
{

    
    const studentApplicationChart = $("#studentApplicationChart")[0];
    const assignedInternChart = $('#assignedInternsChart')[0];
    const monthlyVolumeChart = $('#monthlyVolumeChart')[0];
    const vacancyPerformanceChart = $("#vacancyPerformanceChart")[0];

    

    if (studentApplicationChart) {
        new Chart(studentApplicationChart, {
            type: "bar",
            data: {
                labels: ['Viewed', 'Approved', 'Rejected'],
                datasets: [{
                    label: 'Applied Jobs',
                    data: [10, 20, 5],
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

if (assignedInternChart) {
        $.ajax({
            url: '../../includes/get_chart_data.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const liveData = response.data;

                    new Chart(assignedInternChart, {
                        type: 'doughnut',
                        data: {
                            labels: ['Still Applying', 'Not Applying', 'Placed'],
                            datasets: [{
                                data: [
                                    liveData.applying,     
                                    liveData.not_applying, 
                                    liveData.placed        
                                ],
                                backgroundColor: [
                                    '#FFC107', 
                                    '#D32F2F', 
                                    '#2E7D32' 
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 15,
                                        font: { family: 'sans-serif', size: 12, weight: '500' }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error("Database chart reporting error:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX execution failed:", error);
            }
        });
    }

    if (monthlyVolumeChart) {
        new Chart(monthlyVolumeChart, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Total Applications Received',
                    data: [12, 19, 8, 15, 24, 32],
                    backgroundColor: "#111E4B",
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#E2E8F0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    if (vacancyPerformanceChart) {
        new Chart(vacancyPerformanceChart, {
            type: 'bar',
            data: {
                labels: ['Software Engineer Intern', 'Data Analyst Intern', 'UI/UX Designer Intern'],
                datasets: [{
                    label: 'Applicants Registered',
                    data: [15, 9, 6],
                    backgroundColor: "#111E4B",
                    borderRadius: 4,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y', // Flips layout matrix horizontally
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 3
                        }
                    },
                    y: {
                        grid: { display: false }
                    }
                } // Properly containing both x and y structures
            }
        });
    }
}

    if (studentApplicationChart) {
        new Chart(studentApplicationChart, {
            type: "bar",
            data: {
                labels: ['Viewed', 'Approved', 'Rejected'],
                datasets: [{
                    label: 'Applied Jobs',
                    data: [10, 20, 5],
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

    if (assignedInternChart) {
        new Chart(assignedInternChart, {
            type: 'doughnut',
            data: {
                labels: ['Start Applying', 'Not Applying', 'Placed'],
                datasets: [{
                    data: [4, 1, 1], 
                    backgroundColor: [
                        '#FFC107', // Still Applying -> Deep, High-Contrast Amber Yellow
                        '#D32F2F', // Not Applying   -> Harsh Crimson Warning Red!
                        '#2E7D32'  // Placed         -> Deep, Solid Dark Green
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Creates a premium, clean thin ring shape
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { family: 'sans-serif', size: 12, weight: '500' }
                        }
                    }
                }
            }
        });
    }

    if (monthlyVolumeChart) {
        new Chart(monthlyVolumeChart, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Total Applications Received',
                    data: [12, 19, 8, 15, 24, 32],
                    backgroundColor: "#111E4B",
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#E2E8F0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    if (vacancyPerformanceChart) {
        new Chart(vacancyPerformanceChart, {
            type: 'bar',
            data: {
                labels: ['Software Engineer Intern', 'Data Analyst Intern', 'UI/UX Designer Intern'],
                datasets: [{
                    label: 'Applicants Registered',
                    data: [15, 9, 6],
                    backgroundColor: "#111E4B",
                    borderRadius: 4,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y', // Flips layout matrix horizontally
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 3
                        }
                    },
                    y: {
                        grid: { display: false }
                    }
                } // Properly containing both x and y structures
            }
        });
    }
}
