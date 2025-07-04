<?php
$servername = "localhost";
$username = "root";        // Or your DB username
$password = "";            // Or your DB password
$dbname = "fintrack_v2"; // ✅ Put your actual DB name here

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>
