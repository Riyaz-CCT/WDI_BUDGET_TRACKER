<?php
include 'auth.php';
include 'config.php'; // Adjust the path to your DB connection file
$user_id = $_SESSION['user_id']; 

// view_receipt.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "fintrack_v2";

// Connect
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    die("Invalid request.");
}

$stmt = $conn->prepare("SELECT receipt FROM transactions WHERE id = ?");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Receipt not found.");
}

$stmt->bind_result($receiptData);
$stmt->fetch();

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$contentType = finfo_buffer($finfo, $receiptData);
finfo_close($finfo);

header("Content-Type: $contentType");
echo $receiptData;

$stmt->close();
$conn->close();
