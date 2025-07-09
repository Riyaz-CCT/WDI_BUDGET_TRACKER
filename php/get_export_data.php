<?php
ob_start();
require 'auth.php';
require_once 'config.php';

$user_id = $_SESSION['user_id'];

//$userId = 1;

$sql = "SELECT 
            t.date, 
            c.name AS category, 
            t.description, 
            t.item, 
            t.type, 
            t.amount, 
            t.payment_method 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?
        ORDER BY t.date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

// CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="transactions.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Headers
fputcsv($output, ['Date', 'Category', 'Description', 'Item', 'Type', 'Amount', 'Payment Method']);

// Data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['date'],
            $row['category'],
            $row['description'],
            $row['item'],
            $row['type'],
            $row['amount'],
            $row['payment_method']
        ]);
    }
} else {
    // Optional: write a message row if no data
    fputcsv($output, ["No data found for this user."]);
}

fclose($output);
$stmt->close();
$conn->close();
ob_end_flush();
exit();
?>
