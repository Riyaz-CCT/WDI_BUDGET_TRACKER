<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database config
$host = "localhost";
$user = "root";
$pass = "";
$db   = "fintrack_v2";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Check if table exists
$check = $conn->query("SHOW TABLES LIKE 'expenses'");
if ($check->num_rows == 0) {
    die("❌ Table 'expenses' does not exist in database.");
}

// Get form inputs
$date        = $_POST['expense-date'] ?? '';
$category    = $_POST['expense-category'] ?? '';
$amount      = $_POST['expense-amount'] ?? '';
$transaction = $_POST['transaction-type'] ?? '';
$payment     = $_POST['payment-method'] ?? '';
$description = $_POST['expense-description'] ?? '';
$fileName    = "";

// File upload
if (isset($_FILES['expense-file']) && $_FILES['expense-file']['error'] == 0) {

    $uploadDir = "uploads/";
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = basename($_FILES['expense-file']['name']);
    move_uploaded_file($_FILES['expense-file']['tmp_name'], $uploadDir . $fileName);
}

// Print values to check
echo "<h3>Debug:</h3>";
echo "Date: $date<br>";
echo "Category: $category<br>";
echo "Amount: $amount<br>";
echo "Transaction Type: $transaction<br>";
echo "Payment Method: $payment<br>";
echo "Description: $description<br>";
echo "File Name: $fileName<br>";

// SQL Insert
$sql = "INSERT INTO expenses (expense_date, category, amount, transaction_type, payment_method, description, file_name)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

$amount = floatval($amount);
$stmt->bind_param("ssdssss", $date, $category, $amount, $transaction, $payment, $description, $fileName);

if ($stmt->execute()) {
    echo "<br>✅ Expense saved successfully!";
} else {
    echo "<br>❌ Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
