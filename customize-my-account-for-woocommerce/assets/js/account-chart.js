document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('sbCustomerSpendingChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: sbChartData.labels,
            datasets: [{
                label: wcmamtxchart.amountspent_label,
                data: sbChartData.values,
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

});