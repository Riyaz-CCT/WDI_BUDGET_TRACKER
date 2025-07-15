<?php
$conn = new mysqli("localhost", "root", "", "fintrack_v2");
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT receipt FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($receipt);
$stmt->fetch();

if ($stmt->num_rows > 0 && $receipt) {
    $finfo = finfo_open();
    $mime = finfo_buffer($finfo, $receipt, FILEINFO_MIME_TYPE);
    header("Content-Type: $mime");
    echo $receipt;
} else {
    echo "No receipt found.";
}
?>
