<?php
require_once 'auth.php';
require_once 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../pages/login.php");
    exit();
}

$conn->begin_transaction();

try {
    // ✅ Delete from related tables first
    // Delete goals
    $stmt = $conn->prepare("DELETE FROM goals WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Delete transactions (if this table exists)
    $stmt = $conn->prepare("DELETE FROM transactions WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // ✅ Finally delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // ✅ Commit transaction
    $conn->commit();

    // ✅ Destroy session and redirect to login
    session_destroy();
    header("Location: ../pages/login.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Failed to delete account: " . $e->getMessage();
}
?>
