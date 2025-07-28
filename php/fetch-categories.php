<?php
include 'auth.php';
include 'config.php';

$user_id = $_SESSION['user_id']; 

header('Content-Type: application/json');

$categories = [];

// Fetch categories that are either global (user_id IS NULL) or specific to the current user
$stmt = $conn->prepare("SELECT name FROM categories WHERE user_id IS NULL OR user_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['name'];
}

echo json_encode(["categories" => $categories]);
?>
