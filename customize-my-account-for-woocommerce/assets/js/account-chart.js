function sbInitSpendingChart() {

    const canvas = document.getElementById('sbCustomerSpendingChart');

    if (!canvas || typeof Chart === 'undefined' || typeof sbChartData === 'undefined') {
        return;
    }

    if (canvas._sbChartInitialized) {
        return;
    }
    canvas._sbChartInitialized = true;

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

}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', sbInitSpendingChart);
} else {
    sbInitSpendingChart();
}