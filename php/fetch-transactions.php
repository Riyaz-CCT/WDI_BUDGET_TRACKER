<?php
include 'auth.php';
include 'config.php'; // Adjust the path to your DB connection file

header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database config
// $host = "localhost";
// $user = "root";
// $pass = "";
// $db   = "fintrack_v2";

// Connect
// $conn = new mysqli($host, $user, $pass, $db);
// if ($conn->connect_error) {
//     die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
// }

$user_id = 18; // Replace with session user ID later
//$user_id = $_SESSION['user_id']; 


$query = "SELECT 
            t.id, 
            c.name AS category, 
            t.item, 
            t.description, 
            t.type AS transaction_type, 
            t.amount, 
            t.date, 
            t.payment_method
          FROM transactions t
          JOIN categories c ON t.category_id = c.id
          WHERE t.user_id = ?
          ORDER BY t.date DESC, t.id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

echo json_encode(["recent_transactions" => $transactions], JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>
