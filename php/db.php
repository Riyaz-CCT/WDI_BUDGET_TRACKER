<?php
$host = "localhost";
$username = "root";  // default in XAMPP
$password = "";      // default in XAMPP
$database = "fintrack_v2";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
