<?php
require_once 'auth.php';
require_once 'config.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// ✅ Step 1: Get only the fields sent from frontend
$name            = $_POST['name']            ?? null;
$phone           = $_POST['phone']           ?? null;
$target_expense  = $_POST['target_expense']  ?? null;
$target_saving   = $_POST['target_saving']   ?? null;
$debt            = $_POST['debt']            ?? null;

$updated_tables = [];

// ✅ Step 2: Update USERS table if name or phone was sent
if ($name !== null || $phone !== null) {
    // Fetch current values to preserve any unchanged fields
    $stmt = $conn->prepare("SELECT name, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $new_name  = $name  !== null ? $name  : $existing['name'];
    $new_phone = $phone !== null ? $phone : $existing['phone'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_name, $new_phone, $user_id);
    $stmt->execute();
    $stmt->close();

    $updated_tables[] = "users";
}

// ✅ Step 3: Update GOALS table if any goal field was sent
if ($target_expense !== null || $target_saving !== null || $debt !== null) {
    $month_year = date('Y-m'); // e.g. "2025-07"

    // Fetch existing values from DB (if row exists)
    $stmt = $conn->prepare("SELECT target_expense, target_saving, debt FROM goals WHERE user_id = ? AND month_year = ?");
    $stmt->bind_param("is", $user_id, $month_year);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_exists = $result->num_rows > 0;

    $existing = $row_exists ? $result->fetch_assoc() : [
        'target_expense' => 0,
        'target_saving' => 0,
        'debt' => 0
    ];
    $stmt->close();

    // Use sent value if available, otherwise fallback to existing
    $new_expense = $target_expense !== null ? $target_expense : $existing['target_expense'];
    $new_saving  = $target_saving  !== null ? $target_saving  : $existing['target_saving'];
    $new_debt    = $debt           !== null ? $debt           : $existing['debt'];

    // INSERT or UPDATE for current month
    $stmt = $conn->prepare("
        INSERT INTO goals (user_id, month_year, target_expense, target_saving, debt)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            target_expense = VALUES(target_expense),
            target_saving = VALUES(target_saving),
            debt = VALUES(debt)
    ");
    $stmt->bind_param("issdd", $user_id, $month_year, $new_expense, $new_saving, $new_debt);
    $stmt->execute();
    $stmt->close();

    $updated_tables[] = "goals";
}

// ✅ Step 4: Respond with success
echo json_encode([
    'success' => true,
    'updated_tables' => $updated_tables
]);
?>
