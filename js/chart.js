$(document).ready(function () {
  let currentData;
  let lineChartInstance, pieChartInstance, barChart2Instance;

  const lineCtx = $('#barChart')[0].getContext('2d');
  const summaryPieCtx = $('#summaryPieChart')[0].getContext('2d');
  const ctx2 = $('#barChart2')[0].getContext('2d');

  // Fetch JSON data
  $.getJSON('../php/get_graph_data.php', function (json) {
    currentData = json;

    const initialRange = $('#rangeSelect').val();
    const initialPieType = $('#pieRangeSelect').val();

    renderLineChart(currentData[initialRange]);
    renderPieChart(currentData["monthly"], initialPieType);
    renderBarChart2(currentData[initialRange]);
  });

  // ========== RENDER LINE CHART ==========
  function renderLineChart(data) {
    if (lineChartInstance) lineChartInstance.destroy();

    lineChartInstance = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: data.income.labels,
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
            borderColor: '#ef5350',
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

  // ========== RENDER PIE CHART ==========
  function renderPieChart(data, type = "summary") {
    if (pieChartInstance) pieChartInstance.destroy();

    let labels = [], values = [];
    const baseColors = [
      '#3949ab', '#5c6bc0', '#7986cb', '#9fa8da', '#c5cae9', '#b3c2f2',
      '#d1d9ff', '#bac8ff', '#8c9eff', '#536dfe', '#3d5afe', '#304ffe'
    ];

    if (type === "summary") {
      const currentMonth = new Date().getMonth(); // 0-11

      const income = data.income.trend[currentMonth] || 0;
      const expense = data.expense.trend[currentMonth] || 0;
      const saving = income - expense;

      labels = ['Income', 'Expenses', 'Savings'];
      values = [income, expense, saving];
    } else if (type === "method" && data.expense.method) {
      labels = data.expense.method.labels;
      values = data.expense.method.data;
    } else if (type === "category" && data.expense.category) {
      labels = data.expense.category.labels;
      values = data.expense.category.data;
    }

    if (labels.length === 0 || values.length === 0) {
      $('#summaryPieChart').replaceWith('<div id="summaryPieChart">No data available</div>');
      return;
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

  // ========== RENDER BAR CHART ==========
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
          ticks: { color: '#2c2c2c', font: { size: 12 } },
          grid: { color: '#e0e0e0' }
        },
        x: {
          ticks: { color: '#2c2c2c', font: { size: 12 } },
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

  // ========== EVENT HANDLERS ==========
  $('#rangeSelect').on("change", function () {
    const range = $(this).val();
    renderLineChart(currentData[range]);
  });

  $('#bar2RangeSelect').on("change", function () {
    const range = $(this).val();
    renderBarChart2(currentData[range]);
  });

  $('#pieRangeSelect').on("change", function () {
    const type = $(this).val(); // summary, method, or category
    renderPieChart(currentData["monthly"], type); // Always use monthly data
  });
});
