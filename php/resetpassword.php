<?php
session_start();
include('../php/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate both fields are filled
    if (empty($password) || empty($confirm_password)) {
        echo "<script>alert('Both password fields are required.'); window.history.back();</script>";
        exit();
    }

    // ✅ Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // ✅ Check if session has the email
    if (!isset($_SESSION['email'])) {
        echo "<script>alert('Session expired. Please try again.'); window.location.href='../pages/forgot-password.html';</script>";
        exit();
    }

    $email = $_SESSION['email'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        session_destroy();
        echo "<script>alert('Password reset successful! Please login.'); window.location.href='../pages/login.php';</script>";
    } else {
        echo "<script>alert('Error updating password.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>