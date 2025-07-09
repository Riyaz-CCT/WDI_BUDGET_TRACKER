<?php
// php/get_dashboard_data.php
include 'auth.php';
include 'config.php';
$user_id = $_SESSION['user_id'];  // Replace later with $_SESSION['user_id']



// ========== Date Variables ==========
$currentMonth = date('m');
$currentYear = date('Y');
$prevMonth = date('m', strtotime('-1 month'));
$prevYear = date('Y', strtotime('-1 month'));

// ========== Helper Function ==========
function getTotal($conn, $user_id, $type, $month, $year) {
    $sql = "SELECT SUM(amount) AS total 
            FROM transactions 
            WHERE user_id = ? AND type = ? 
            AND MONTH(date) = ? AND YEAR(date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $user_id, $type, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

// ========== Get Totals ==========
$income_now = getTotal($conn, $user_id, 'Income', $currentMonth, $currentYear);
$income_prev = getTotal($conn, $user_id, 'Income', $prevMonth, $prevYear);

$expense_now = getTotal($conn, $user_id, 'Expense', $currentMonth, $currentYear);
$expense_prev = getTotal($conn, $user_id, 'Expense', $prevMonth, $prevYear);

$saving_now = $income_now - $expense_now;
$saving_prev = $income_prev - $expense_prev;

// ========== Percentage Change ==========
function getPercentChange($now, $prev) {
    if ($prev == 0) return null;
    return round((($now - $prev) / $prev) * 100, 2);
}

$percent_income = getPercentChange($income_now, $income_prev);
$percent_expense = getPercentChange($expense_now, $expense_prev);
$percent_saving = getPercentChange($saving_now, $saving_prev);

// ========== Recent Transactions ==========
$sql = "SELECT item, amount 
        FROM transactions 
        WHERE user_id = ? 
        ORDER BY date DESC 
        LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$recent_transactions = [];
while ($row = $result->fetch_assoc()) {
    $recent_transactions[] = $row;
}


// ========== Final Output ==========
echo json_encode([
    'income' => round($income_now),
    'expense' => round($expense_now),
    'saving' => round($saving_now),
    'percent_income' => $percent_income,
    'percent_expense' => $percent_expense,
    'percent_saving' => $percent_saving,
    'recent_transactions' => $recent_transactions
]);


?>