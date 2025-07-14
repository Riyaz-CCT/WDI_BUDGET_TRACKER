<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// ✅ Require user to be logged in
if (!isset($_SESSION['user_id'])) {
    die("❌ User not logged in. Please log in to continue.");
}

// ✅ Use user_id from session
$user_id = $_SESSION['user_id'];

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

// Check if transactions table exists
$check = $conn->query("SHOW TABLES LIKE 'transactions'");
if ($check->num_rows == 0) {
    die("❌ Table 'transactions' does not exist in database.");
}

// Get form inputs
$date        = $_POST['expense-date'] ?? date('Y-m-d');
$category    = $_POST['expense-category'] ?? '';
$amount      = $_POST['expense-amount'] ?? 0;
$type        = $_POST['transaction-type'] ?? 'Expense';
$payment     = $_POST['payment-method'] ?? '';
$description = $_POST['expense-description'] ?? '';
$item        = $_POST['expense-item'] ?? '';
$receipt_url = "";

// Handle file upload
if (isset($_FILES['expense-file']) && $_FILES['expense-file']['error'] === 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = basename($_FILES['expense-file']['name']);
    $targetPath = $uploadDir . $fileName;
    move_uploaded_file($_FILES['expense-file']['tmp_name'], $targetPath);
    $receipt_url = $targetPath;
}

// Get category_id from category name and type
$categoryStmt = $conn->prepare("SELECT id FROM categories WHERE name = ? AND type = ?");
$categoryStmt->bind_param("ss", $category, $type);
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();

if ($categoryResult->num_rows === 0) {
    die("❌ Category '$category' of type '$type' not found.");
}

$categoryRow = $categoryResult->fetch_assoc();
$category_id = $categoryRow['id'];
$categoryStmt->close();

// Insert into transactions
$sql = "INSERT INTO transactions 
        (user_id, category_id, item, description, type, amount, date, payment_method, receipt_url)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

$amount = floatval($amount);
$stmt->bind_param("iissssdss", $user_id, $category_id, $item, $description, $type, $amount, $date, $payment, $receipt_url);

// Optional debug output
echo "<h3>Debug Info:</h3>";
echo "User ID: $user_id<br>";
echo "Date: $date<br>";
echo "Category ID: $category_id ($category)<br>";
echo "Amount: $amount<br>";
echo "Type: $type<br>";
echo "Payment Method: $payment<br>";
echo "Item: $item<br>";
echo "Description: $description<br>";
echo "Receipt URL: $receipt_url<br>";

// Execute
if ($stmt->execute()) {
    echo "<br>✅ Expense saved successfully!";
} else {
    echo "<br>❌ Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
