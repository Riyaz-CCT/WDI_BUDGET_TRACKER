$(document).ready(function () {
    $.ajax({
        url: '../php/get_cards_data.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // ========== Update numeric values ==========
            $('#income-value').text(`₹${parseFloat(data.income).toLocaleString()}`);
            $('#expense-value').text(`₹${parseFloat(data.expense).toLocaleString()}`);
            $('#savings-value').text(`₹${parseFloat(data.saving).toLocaleString()}`);
            $('#debt-value').text(`₹${parseFloat(data.debt).toLocaleString()}`); // ✅ corrected ID

            // ========== Helper function for % display ==========
            function updatePercentage($element, value, isExpense = false) {
                const percent = parseFloat(value || 0).toFixed(2);
                const sign = value > 0 ? '+' : value < 0 ? '–' : '';
                const color = isExpense
                    ? (value >= 0 ? '#ef5350' : '#2e7d32') // Expense: + → red, – → green
                    : (value >= 0 ? '#2e7d32' : '#ef5350'); // Income/Saving: + → green, – → red
                const cssClass = value >= 0 ? 'up' : 'down';

                $element
                    .text(`${sign}${Math.abs(percent)}%`)
                    .css('color', color)
                    .removeClass('up down')
                    .addClass(cssClass);
            }

            // ========== Update percentages ==========
            updatePercentage($('#income-value').next('.growth'), data.percent_income, false);
            updatePercentage($('#expense-value').next('.growth'), data.percent_expense, true);
            updatePercentage($('#savings-value').next('.growth'), data.percent_saving, false);

            // ✅ Custom logic for debt percent: + = more debt (red), – = less debt (green)
            const percentDebt = parseFloat(data.percent_debt || 0).toFixed(2);
            const $debtGrowth = $('#debt-value').next('.growth'); // ✅ corrected ID
            const isDebtIncreased = percentDebt > 0;
            const debtSign = isDebtIncreased ? '+' : '–';
            const debtColor = isDebtIncreased ? '#ef5350' : '#2e7d32';
            const debtClass = isDebtIncreased ? 'up' : 'down';

            $debtGrowth
                .text(`${debtSign}${Math.abs(percentDebt)}%`)
                .css('color', debtColor)
                .removeClass('up down')
                .addClass(debtClass);

            // ========== Populate table ==========
            populateTable(data.recent_transactions);
        },
        error: function () {
            console.error('Failed to fetch dashboard data.');
        }
    });

    // Function to populate the table and total row
    function populateTable(transactions) {
        const $tbody = $('.table--container table tbody');
        const $tfoot = $('.table--container table tfoot');
        let total = 0;

        $tbody.empty();
        $tfoot.empty();

        $.each(transactions, function (index, transaction) {
            const $row = $('<tr></tr>');
            const $itemCell = $('<td></td>').text(transaction.item);
            const $amountCell = $('<td></td>').text(`₹${parseFloat(transaction.amount).toFixed(2)}`);

            $row.append($itemCell, $amountCell);
            $tbody.append($row);

            total += parseFloat(transaction.amount);
        });

        const $totalRow = $('<tr></tr>');
        const $totalLabel = $('<td><strong>Recent Total</strong></td>');
        const $totalValue = $('<td></td>').text(`₹${total.toFixed(2)}`);

        $totalRow.append($totalLabel, $totalValue);
        $tfoot.append($totalRow);
    }
});
