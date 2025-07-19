<?php
include 'auth.php';
include 'config.php'; // Adjust the path to your DB connection file
$user_id = $_SESSION['user_id']; 

$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    echo "<script>alert('Invalid request.'); history.back();</script>";
    exit();
}

$stmt = $conn->prepare("SELECT receipt FROM transactions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('Receipt not found.'); history.back();</script>";
    exit();
}

$stmt->bind_result($receiptData);
$stmt->fetch();

if (empty($receiptData)) {
    echo "<script>alert('No receipt available for this transaction.'); history.back();</script>";
    exit();
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$contentType = finfo_buffer($finfo, $receiptData);
finfo_close($finfo);

header("Content-Type: $contentType");
echo $receiptData;

$stmt->close();
$conn->close();
?>