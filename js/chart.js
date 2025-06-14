const chartIds = ['barChart', 'lineChart', 'pieChart'];
let currentIndex = 1; // Show line chart by default

function showChart(index) {
    chartIds.forEach((id, i) => {
        const canvas = document.getElementById(id);
        canvas.style.display = i === index ? 'block' : 'none';
    });
}

function nextChart() {
    currentIndex = (currentIndex + 1) % chartIds.length;
    showChart(currentIndex);
}

function prevChart() {
    currentIndex = (currentIndex - 1 + chartIds.length) % chartIds.length;
    showChart(currentIndex);
}

// Chart.js setup
const barCtx = document.getElementById('barChart').getContext('2d');
const lineCtx = document.getElementById('lineChart').getContext('2d');
const pieCtx = document.getElementById('pieChart').getContext('2d');

new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr'],
        datasets: [{
            label: 'Bar Earnings',
            data: [200, 150, 300, 250],
            backgroundColor: 'rgba(113,99,186,0.6)',
            borderColor: 'rgba(113,99,186,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr'],
        datasets: [{
            label: 'Website Revenue',
            data: [100, 200, 150, 300],
            borderColor: 'rgba(113, 99, 186, 1)',         // Primary purple
            backgroundColor: 'rgba(113, 99, 186, 0.2)',   // Soft transparent purple
            pointBackgroundColor: 'rgba(113, 99, 186, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(113, 99, 186, 1)',
            borderWidth: 2,
            tension: 0.4  // for smooth curves
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                ticks: {
                    color: '#555'  // soft dark gray for readability
                },
                grid: {
                    color: '#eee'
                }
            },
            x: {
                ticks: {
                    color: '#555'
                },
                grid: {
                    color: '#eee'
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: 'rgba(113, 99, 186, 1)',  // purple text in legend
                    font: {
                        weight: 'bold'
                    }
                }
            }
        }
    }
});

new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Rent', 'Groceries', 'Utilities'],
        datasets: [{
            label: 'Website Theme Pie',
            data: [300, 200, 100],
            backgroundColor: [
                'rgba(113, 99, 186, 1)',     // Primary purple
                'rgba(178, 162, 255, 0.8)',  // Soft lavender (light variant)
                'rgba(237, 237, 237, 1)'     // Light gray (same as your .search--box bg)
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Show line chart on initial load
showChart(currentIndex);
