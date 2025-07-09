<?php

include 'auth.php';
include 'config.php'; // Adjust the path to your DB connection file
$user_id = $_SESSION['user_id']; 
$month = date('Y-m'); // Get current month in 'YYYY-MM' format

$sql = "SELECT target_expense, target_saving FROM goals 
        WHERE user_id = ? AND month_year = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $month);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($row = $result->fetch_assoc()) {
    $response['target_expense'] = (float)$row['target_expense'];
    $response['target_saving'] = (float)$row['target_saving'];
} else {
    $response['target_expense'] = 0;
    $response['target_saving'] = 0;
}

echo json_encode($response);
?>