<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "fintrack_v2");
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

$categories = [];
$res = $conn->query("SELECT name FROM categories ORDER BY name ASC");
while ($row = $res->fetch_assoc()) {
    $categories[] = $row['name'];
}

echo json_encode(["categories" => $categories]);
?>