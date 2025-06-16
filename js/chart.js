// ======================= CHART DISPLAY LOGIC =======================

// IDs of all chart canvases in order
const chartIds = ['barChart', 'lineChart', 'pieChart'];

// Default to showing the second chart (line chart)
let currentIndex = 1;

// Function to display the chart corresponding to the given index
function showChart(index) {
    chartIds.forEach((id, i) => {
        const canvas = document.getElementById(id);
        canvas.style.display = i === index ? 'block' : 'none';
    });
}

// Function to show the next chart in the list
function nextChart() {
    currentIndex = (currentIndex + 1) % chartIds.length;
    showChart(currentIndex);
}

// Function to show the previous chart in the list
function prevChart() {
    currentIndex = (currentIndex - 1 + chartIds.length) % chartIds.length;
    showChart(currentIndex);
}


// ======================= CHART INITIALIZATION =======================

// Get context of each canvas element to draw charts on them
const barCtx = document.getElementById('barChart').getContext('2d');
const lineCtx = document.getElementById('lineChart').getContext('2d');
const pieCtx = document.getElementById('pieChart').getContext('2d');


// ----------------------- BAR CHART -----------------------
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


// ----------------------- LINE CHART -----------------------
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr'],
        datasets: [{
            label: 'Website Revenue',
            data: [100, 200, 150, 300],
            borderColor: 'rgba(113, 99, 186, 1)',         // Primary purple
            backgroundColor: 'rgba(113, 99, 186, 0.2)',   // Light purple fill
            pointBackgroundColor: 'rgba(113, 99, 186, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(113, 99, 186, 1)',
            borderWidth: 2,
            tension: 0.4 // Smooth curves
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                ticks: {
                    color: '#555' // Soft gray
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
                    color: 'rgba(113, 99, 186, 1)',
                    font: {
                        weight: 'bold'
                    }
                }
            }
        }
    }
});


// ----------------------- PIE CHART -----------------------
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Rent', 'Groceries', 'Utilities'],
        datasets: [{
            label: 'Website Theme Pie',
            data: [300, 200, 100],
            backgroundColor: [
                'rgba(113, 99, 186, 1)',     // Primary purple
                'rgba(178, 162, 255, 0.8)',  // Soft lavender
                'rgba(237, 237, 237, 1)'     // Light gray
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


// ======================= INITIAL CHART DISPLAY =======================

// Display the default selected chart (line chart)
showChart(currentIndex);
