<?php
include 'auth.php';
include 'config.php'; // Adjust the path to your DB connection file

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

// Dummy user ID (replace with session login later)
//$user_id = 1;
$user_id = $_SESSION['user_id']; 

// Get form inputs
$date         = $_POST['expense-date'] ?? '';
$category     = $_POST['expense-category'] ?? '';
$newCategory  = trim($_POST['new-category'] ?? '');
$item         = $_POST['expense-item'] ?? '';
$description  = $_POST['expense-description'] ?? '';
$type         = $_POST['transaction-type'] ?? '';
$amount       = floatval($_POST['expense-amount'] ?? 0);
$payment      = $_POST['payment-method'] ?? '';
$receiptBlob  = NULL;

// === Handle Category ===
if ($category === 'AddNew' && !empty($newCategory)) {
    // Insert new category if it doesn't exist
    $stmt = $conn->prepare("INSERT IGNORE INTO categories(name) VALUES (?)");
    $stmt->bind_param("s", $newCategory);
    $stmt->execute();
    $stmt->close();

    // Get the ID of the new or existing category
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->bind_param("s", $newCategory);
    $stmt->execute();
    $stmt->bind_result($category_id);
    $stmt->fetch();
    $stmt->close();
} else {
    // Get ID of selected category
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->bind_result($category_id);
    $stmt->fetch();
    $stmt->close();
}

// === Handle File Upload ===
if (isset($_FILES['expense-file']) && $_FILES['expense-file']['error'] == 0) {
    $receiptBlob = file_get_contents($_FILES['expense-file']['tmp_name']);
}

// === Insert into transactions table ===
$sql = "INSERT INTO transactions (
    user_id, category_id, item, description, type, amount, date, payment_method, receipt
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

// Bind params and use the actual $receiptBlob
$stmt->bind_param(
    "iisssdsss",
    $user_id,
    $category_id,
    $item,
    $description,
    $type,
    $amount,
    $date,
    $payment,
    $receiptBlob
);

if ($stmt->execute()) {
    echo "✅ Expense successfully added!";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
