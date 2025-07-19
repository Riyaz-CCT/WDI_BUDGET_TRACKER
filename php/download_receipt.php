<?php
// download_receipt.php

include 'auth.php';
include 'config.php';

$user_id = $_SESSION['user_id'];
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit;
}

// Fetch receipt blob from the database
$stmt = $conn->prepare("SELECT receipt FROM transactions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('Receipt not found or access denied.'); window.history.back();</script>";
    exit;
}

$stmt->bind_result($receiptData);
$stmt->fetch();
$stmt->close();

// ✅ Check if receipt is actually present
if (empty($receiptData)) {
    echo "<script>alert('No receipt uploaded for this transaction.'); window.history.back();</script>";
    exit;
}

// Detect MIME type from binary data
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->buffer($receiptData);

// Determine file extension based on MIME type
$extensions = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'application/pdf' => 'pdf'
];

$extension = $extensions[$mimeType] ?? 'bin';

// Set headers for file download
header("Content-Type: $mimeType");
header("Content-Disposition: attachment; filename=receipt_{$transaction_id}.{$extension}");
header("Content-Length: " . strlen($receiptData));

// Output the receipt binary data
echo $receiptData;

$conn->close();
?>
