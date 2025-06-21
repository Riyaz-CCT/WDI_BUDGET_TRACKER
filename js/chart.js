// ======================= CHART INITIALIZATION =======================

// Declare global chart variables
let currentData;
let lineChartInstance, pieChartInstance, barChart2Instance;

// Get canvas contexts
const lineCtx = document.getElementById('barChart').getContext('2d');
const summaryPieCtx = document.getElementById('summaryPieChart').getContext('2d');
const ctx2 = document.getElementById("barChart2").getContext("2d");

// Load data from JSON and render default charts
fetch("data.json")
  .then(res => res.json())
  .then(json => {
    currentData = json;
    renderLineChart(json.monthly);
    renderPieChart(json.monthly, "summary");
    renderBarChart2(json.monthly);
  });

// ======================= CHART RENDER FUNCTIONS =======================

// Render the Line Chart (Income vs Expense trend)
function renderLineChart(data) {
  if (lineChartInstance) lineChartInstance.destroy();

  lineChartInstance = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: data.income.labels, // X-axis labels
      datasets: [
        {
          label: 'Income ($)',
          data: data.income.trend,
          borderColor: '#3949ab',
          backgroundColor: 'rgba(57, 73, 171, 0.15)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#3949ab'
        },
        {
          label: 'Expense ($)',
          data: data.expense.trend,
          borderColor: '#ef5350', // red color for expenses
          backgroundColor: 'rgba(239, 83, 80, 0.15)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#ef5350'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: '#2c2c2c' },
          grid: { color: '#e0e0e0' }
        },
        x: {
          ticks: { color: '#2c2c2c' },
          grid: { color: '#f4f6fa' }
        }
      },
      plugins: {
        legend: {
          labels: {
            color: '#2c2c2c',
            font: { weight: 'bold' }
          }
        },
        tooltip: {
          callbacks: {
            label: ctx => `$${ctx.parsed.y.toFixed(2)}`
          }
        }
      }
    }
  });
}

// Render Pie Chart (Summary, Method, or Category view)
function renderPieChart(data, type = "summary") {
  if (pieChartInstance) pieChartInstance.destroy();

  let labels = [];
  let values = [];

  const baseColors = [
    '#3949ab', '#5c6bc0', '#7986cb', '#9fa8da', '#c5cae9', '#b3c2f2',
    '#d1d9ff', '#bac8ff', '#8c9eff', '#536dfe', '#3d5afe', '#304ffe'
  ];

  // Choose data based on type
  if (type === "summary") {
    labels = ['Income', 'Expenses', 'Savings'];
    values = [data.income.summary, data.expense.summary, data.saving.summary];
  } else if (type === "method") {
    labels = data.expense.method.labels;
    values = data.expense.method.data;
  } else if (type === "category") {
    labels = data.expense.category.labels;
    values = data.expense.category.data;
  }

  const bgColors = labels.map((_, i) => baseColors[i % baseColors.length]);

  pieChartInstance = new Chart(summaryPieCtx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        label: 'Breakdown',
        data: values,
        backgroundColor: bgColors,
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 12
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#2c2c2c',
            font: {
              family: "'Segoe UI', sans-serif",
              size: 14,
              weight: 'bold'
            },
            padding: 18
          }
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              const value = context.parsed;
              const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
              const percent = ((value / total) * 100).toFixed(1);
              return `${context.label}: $${value} (${percent}%)`;
            }
          }
        }
      }
    }
  });
}

// Render Alternate Bar Chart (Expenses only)
function renderBarChart2(data) {
  if (barChart2Instance) barChart2Instance.destroy();

  const bgColors = [
    '#3949ab', '#5c6bc0', '#7986cb', '#9fa8da',
    '#c5cae9', '#b3c2f2', '#d1d9ff', '#bac8ff',
    '#8c9eff', '#536dfe', '#3d5afe', '#304ffe'
  ];

  const expenseBarChartData = {
    labels: data.expense.labels,
    datasets: [{
      label: "Expenses ($)",
      data: data.expense.trend,
      backgroundColor: data.expense.trend.map((_, i) => bgColors[i % bgColors.length]),
      borderWidth: 0,
      borderRadius: 10
    }]
  };

  const expenseBarChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function (context) {
            return `$${context.parsed.y.toFixed(2)}`;
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          color: '#2c2c2c',
          font: { size: 12 }
        },
        grid: { color: '#e0e0e0' }
      },
      x: {
        ticks: {
          color: '#2c2c2c',
          font: { size: 12 }
        },
        grid: { display: false }
      }
    }
  };

  barChart2Instance = new Chart(ctx2, {
    type: "bar",
    data: expenseBarChartData,
    options: expenseBarChartOptions
  });
}

// ======================= CHART DROPDOWN EVENT HANDLERS =======================

// Update line chart when range changes
document.getElementById("rangeSelect").addEventListener("change", (e) => {
  const range = e.target.value;
  renderLineChart(currentData[range]);
});

// Update alternate bar chart when range changes
document.getElementById("bar2RangeSelect").addEventListener("change", (e) => {
  const range = e.target.value;
  renderBarChart2(currentData[range]);
});

// Update pie chart when type changes
document.getElementById("pieRangeSelect").addEventListener("change", (e) => {
  const val = e.target.value;

  let chartType = "summary";
  let dataRange = "monthly";

  if (val === "weekly") {
    chartType = "method";
    dataRange = "weekly";
  } else if (val === "yearly") {
    chartType = "category";
    dataRange = "yearly";
  }

  renderPieChart(currentData[dataRange], chartType);
});
