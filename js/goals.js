$(document).ready(function () {
  updateGoalProgressWithStyle();
});

function updateGoalProgressWithStyle() {
  $.when(
    $.getJSON('../php/get_graph_data.php'),
    $.getJSON('../php/get_goals_data.php')
  ).done(function (graphRes, goalRes) {
    const graphData = graphRes[0];
    const goalData = goalRes[0];
    const currentMonth = new Date().getMonth();

    // Get actual values
    const income = graphData.monthly.income.trend[currentMonth] || 0;
    const expense = graphData.monthly.expense.trend[currentMonth] || 0;
    const saving = income - expense;

    // Get goal values
    const targetExpense = parseFloat(goalData.target_expense) || 0;
    const targetSaving = parseFloat(goalData.target_saving) || 0;

    let expensePercent = 0;
    let savingPercent = 0;

    // ====== CONDITIONAL CHECK ======
    if (targetExpense === 0 && targetSaving === 0) {
      // If both goals are zero, show 0%
      expensePercent = 0;
      savingPercent = 0;
    } else {
      // Normal calculation
      expensePercent = targetExpense > 0 ? ((expense / targetExpense) * 100).toFixed(2) : 0;
      savingPercent = targetSaving > 0 ? ((saving / targetSaving) * 100).toFixed(2) : 0;
    }

    // Update spans
    $('#progress-expense-value').text(`${expensePercent}%`);
    $('#progress-savings-value').text(`${savingPercent}%`);

    // Update progress bar widths
    $('.progress-bar-fill[data-type="expense"]')
      .css('width', `${Math.min(expensePercent, 100)}%`)
      .attr('data-percent', expensePercent);

    $('.progress-bar-fill[data-type="saving"]')
      .css('width', `${Math.min(savingPercent, 100)}%`)
      .attr('data-percent', savingPercent);

    // Now style based on updated values
    styleProgressBars();
  }).fail(function () {
    console.error("❌ Failed to fetch goal or graph data.");
  });
}

function styleProgressBars() {
  $('.progress-bar-fill').each(function () {
    const $fill = $(this);
    const percent = parseFloat($fill.attr('data-percent')) || 0;
    const type = $fill.data('type'); // 'expense' or 'saving'

    // Remove existing color classes
    $fill.removeClass('green yellow red');

    let color;

    if (type === 'expense') {
      if (percent > 70) {
        color = 'red';
      } else if (percent >= 45) {
        color = 'yellow';
      } else {
        color = 'green';
      }
    } else if (type === 'saving') {
      if (percent > 70) {
        color = 'green';
      } else if (percent >= 45) {
        color = 'yellow';
      } else {
        color = 'red';
      }
    }

    $fill.addClass(color);
  });
}
