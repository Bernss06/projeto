function createChart(canvasId, labels, data) {
    var ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(124, 58, 237, 0.8)', // Roxo
                    'rgba(236, 72, 153, 0.8)', // Rosa
                    'rgba(59, 130, 246, 0.8)', // Azul
                    'rgba(16, 185, 129, 0.8)', // Verde
                    'rgba(245, 158, 11, 0.8)'  // Laranja
                ],
                borderColor: [
                    'rgba(124, 58, 237, 1)',
                    'rgba(236, 72, 153, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: "'Poppins', sans-serif",
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: { display: false },
                x: { display: false }
            }
        }
    });
}
