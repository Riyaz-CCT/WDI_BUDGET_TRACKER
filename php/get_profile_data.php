<?php
// php/get_profile_data.php

include 'auth.php';
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get user info (phone, created_at)
$sql_user = "SELECT phone, created_at FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc() ?? [];

$phone = $user_data['phone'] ?? '';
$created_at = $user_data['created_at'] ?? null;
$member_since = $created_at ? date('M Y', strtotime($created_at)) : null;

// Get current month goals
$month_year = date('Y-m');
$sql_goals = "SELECT debt, target_expense, target_saving, note 
              FROM goals 
              WHERE user_id = ? AND month_year = ?";
$stmt_goals = $conn->prepare($sql_goals);
$stmt_goals->bind_param("is", $user_id, $month_year);
$stmt_goals->execute();
$result_goals = $stmt_goals->get_result();
$goals = $result_goals->fetch_assoc() ?? [];

// Merge everything into response
echo json_encode([
    'name' => $_SESSION['name'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'phone' => $phone,
    'member_since' => $member_since,
    'debt' => $goals['debt'] ?? 0,
    'target_expense' => $goals['target_expense'] ?? 0,
    'target_saving' => $goals['target_saving'] ?? 0,
    'note' => $goals['note'] ?? null
]);
?>
