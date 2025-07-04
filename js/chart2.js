$(document).ready(function () {
  let currentData;
  let lineChart, pieChart;

  const lineCtx = $('#barChart')[0].getContext('2d');
  const pieCtx = $('#summaryPieChart')[0].getContext('2d');

  $.getJSON('../pages/data.json', function (json) {
    currentData = json;
    renderLineChart(json.monthly);
    renderPieChart(json.monthly, 'summary');
  });

  function renderLineChart(data) {
    if (lineChart) lineChart.destroy();

    lineChart = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: data.income.labels,
        datasets: [
          {
            label: 'Income',
            data: data.income.trend,
            borderColor: '#4caf50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            fill: true,
            tension: 0.4
          },
          {
            label: 'Expense',
            data: data.expense.trend,
            borderColor: '#f44336',
            backgroundColor: 'rgba(244, 67, 54, 0.2)',
            fill: true,
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  function renderPieChart(data, type = 'summary') {
    if (pieChart) pieChart.destroy();

    let labels = [], values = [];

    if (type === 'summary') {
      labels = ['Income', 'Expenses', 'Savings'];
      values = [data.income.summary, data.expense.summary, data.saving.summary];
    } else if (type === 'method') {
      labels = data.expense.method.labels;
      values = data.expense.method.data;
    } else if (type === 'category') {
      labels = data.expense.category.labels;
      values = data.expense.category.data;
    }

    pieChart = new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: ['#2196f3', '#ff9800', '#9c27b0', '#4caf50', '#f44336']
        }]
      },
      options: {
        responsive: true
      }
    });
  }

  $('#rangeSelect').on('change', function () {
    const range = $(this).val();
    renderLineChart(currentData[range]);
  });

  $('#pieRangeSelect').on('change', function () {
    const value = $(this).val();
    let chartType = 'summary', dataRange = 'monthly';

    if (value === 'weekly') {
      chartType = 'method';
      dataRange = 'weekly';
    } else if (value === 'yearly') {
      chartType = 'category';
      dataRange = 'yearly';
    }

    renderPieChart(currentData[dataRange], chartType);
  });
});
