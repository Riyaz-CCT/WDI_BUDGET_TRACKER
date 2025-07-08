<?php
header('Content-Type: application/json');

$pdo = new PDO("mysql:host=localhost;dbname=fintrack_v2;charset=utf8mb4", "root", "");
$user_id = 1;

function getLastNDays($n, $format = 'D') {
    $labels = [];
    $dates = [];
    for ($i = $n - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $dates[$date] = 0;
        $labels[] = date($format, strtotime($date));
    }
    return [$dates, $labels];
}

function getSummaryData($pdo, $user_id, $type, $dateRangeSql, &$labels, $labelFormat = 'D') {
    list($dataMap, $labels) = getLastNDays(7, $labelFormat);
    $stmt = $pdo->prepare("SELECT DATE(date) AS d, SUM(amount) AS total FROM transactions WHERE user_id = :uid AND type = :type AND date BETWEEN $dateRangeSql GROUP BY d");
    $stmt->execute(['uid' => $user_id, 'type' => $type]);
    foreach ($stmt->fetchAll(PDO::FETCH_KEY_PAIR) as $date => $amount) {
        if (isset($dataMap[$date])) $dataMap[$date] = (float)$amount;
    }
    return [array_sum($dataMap), array_values($dataMap), $labels];
}

function getMonthlyData($pdo, $user_id, $type) {
    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $data = array_fill(0, 12, 0);
    $stmt = $pdo->prepare("SELECT MONTH(date) AS m, SUM(amount) AS total FROM transactions WHERE user_id = :uid AND type = :type AND YEAR(date) = YEAR(CURDATE()) GROUP BY m");
    $stmt->execute(['uid' => $user_id, 'type' => $type]);
    foreach ($stmt->fetchAll(PDO::FETCH_KEY_PAIR) as $month => $amount) {
        $data[(int)$month - 1] = (float)$amount;
    }
    return [array_sum($data), $data, $months];
}

function getMonthlyExpenseMethod($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT payment_method AS label, SUM(amount) AS total FROM transactions WHERE user_id = :uid AND type = 'Expense' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) GROUP BY payment_method");
    $stmt->execute(['uid' => $user_id]);
    $labels = [];
    $data = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $labels[] = $row['label'] ?? 'Unknown';
        $data[] = (float)$row['total'];
    }
    return ['labels' => $labels, 'data' => $data];
}

function getMonthlyExpenseCategory($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT c.name AS label, SUM(t.amount) AS total FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = :uid AND t.type = 'Expense' AND MONTH(t.date) = MONTH(CURDATE()) AND YEAR(t.date) = YEAR(CURDATE()) GROUP BY c.name");
    $stmt->execute(['uid' => $user_id]);
    $labels = [];
    $data = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $labels[] = $row['label'];
        $data[] = (float)$row['total'];
    }
    return ['labels' => $labels, 'data' => $data];
}

function getYearlyData($pdo, $user_id, $type) {
    $data = [];
    $labels = [];
    $stmt = $pdo->prepare("SELECT YEAR(date) AS y, SUM(amount) AS total FROM transactions WHERE user_id = :uid AND type = :type GROUP BY y ORDER BY y");
    $stmt->execute(['uid' => $user_id, 'type' => $type]);
    foreach ($stmt->fetchAll(PDO::FETCH_KEY_PAIR) as $year => $amount) {
        $labels[] = $year;
        $data[] = (float)$amount;
    }
    return [array_sum($data), $data, $labels];
}

function getRecentTransactions($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT item, amount FROM transactions WHERE user_id = :uid ORDER BY date DESC LIMIT 8");
    $stmt->execute(['uid' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Build JSON
list($weeklyIncomeSummary, $weeklyIncomeTrend, $weeklyIncomeLabels) = getSummaryData($pdo, $user_id, 'Income', "CURDATE() - INTERVAL 6 DAY AND CURDATE()", $labels);
list($weeklyExpenseSummary, $weeklyExpenseTrend, $weeklyExpenseLabels) = getSummaryData($pdo, $user_id, 'Expense', "CURDATE() - INTERVAL 6 DAY AND CURDATE()", $labels);

list($monthlyIncomeSummary, $monthlyIncomeTrend, $monthlyIncomeLabels) = getMonthlyData($pdo, $user_id, 'Income');
list($monthlyExpenseSummary, $monthlyExpenseTrend, $monthlyExpenseLabels) = getMonthlyData($pdo, $user_id, 'Expense');
$monthlyExpenseMethod = getMonthlyExpenseMethod($pdo, $user_id);
$monthlyExpenseCategory = getMonthlyExpenseCategory($pdo, $user_id);

list($yearlyIncomeSummary, $yearlyIncomeTrend, $yearlyIncomeLabels) = getYearlyData($pdo, $user_id, 'Income');
list($yearlyExpenseSummary, $yearlyExpenseTrend, $yearlyExpenseLabels) = getYearlyData($pdo, $user_id, 'Expense');

$response = [
    'weekly' => [
        'income' => [
            'summary' => $weeklyIncomeSummary,
            'trend'   => $weeklyIncomeTrend,
            'labels'  => $weeklyIncomeLabels
        ],
        'expense' => [
            'summary' => $weeklyExpenseSummary,
            'trend'   => $weeklyExpenseTrend,
            'labels'  => $weeklyExpenseLabels
        ],
        'saving' => [
            'summary' => $weeklyIncomeSummary - $weeklyExpenseSummary,
            'skills' => [
                'labels' => ["Discipline", "Planning", "Tracking", "Consistency"],
                'data' => [80, 70, 85, 75]
            ]
        ]
    ],
    'monthly' => [
        'income' => [
            'summary' => $monthlyIncomeSummary,
            'trend'   => $monthlyIncomeTrend,
            'labels'  => $monthlyIncomeLabels
        ],
        'expense' => [
            'summary' => $monthlyExpenseSummary,
            'trend'   => $monthlyExpenseTrend,
            'labels'  => $monthlyExpenseLabels,
            'method'  => $monthlyExpenseMethod,
            'category'=> $monthlyExpenseCategory
        ],
        'saving' => [
            'summary' => $monthlyIncomeSummary - $monthlyExpenseSummary,
            'skills' => [
                'labels' => ["Spending", "Saving", "Investing", "Budgeting"],
                'data' => [75, 65, 80, 70]
            ]
        ]
    ],
    'yearly' => [
        'income' => [
            'summary' => $yearlyIncomeSummary,
            'trend'   => $yearlyIncomeTrend,
            'labels'  => $yearlyIncomeLabels
        ],
        'expense' => [
            'summary' => $yearlyExpenseSummary,
            'trend'   => $yearlyExpenseTrend,
            'labels'  => $yearlyExpenseLabels
        ],
        'saving' => [
            'summary' => $yearlyIncomeSummary - $yearlyExpenseSummary,
            'skills' => [
                'labels' => ["Goal Setting", "Emergency Fund", "Retirement", "Investments"],
                'data' => [90, 85, 80, 95]
            ]
        ]
    ],
    'recent_transactions' => getRecentTransactions($pdo, $user_id)
];

echo json_encode($response, JSON_PRETTY_PRINT);
