document.addEventListener("DOMContentLoaded", function () {

    const data = window.dashboardData;

    // Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: data.revenueDates,
            datasets: [{
                label: 'Revenue (₱)',
                data: data.revenueAmounts,
                borderColor: '#00d4ff',
                backgroundColor: 'rgba(0, 212, 255, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#00d4ff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#8b92b8',
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#8b92b8',
                        font: { size: 11 }
                    },
                    grid: { color: '#1a1f3a' }
                },
                x: {
                    ticks: {
                        color: '#8b92b8',
                        font: { size: 11 }
                    },
                    grid: { color: '#1a1f3a' }
                }
            }
        }
    });
});