<?php
require_once 'auth.php';
require_once 'config.php';

header('Content-Type: application/json');

// Check for valid session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$current_month = date('Y-m'); // Format: YYYY-MM

// Only fetch top 10 expense transactions
$query = "
    SELECT item, amount 
    FROM transactions 
    WHERE user_id = ? 
      AND type = 'Expense' 
      AND DATE_FORMAT(date, '%Y-%m') = ? 
    ORDER BY amount DESC 
    LIMIT 10
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param('is', $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

echo json_encode(['top_transactions' => $transactions]);
