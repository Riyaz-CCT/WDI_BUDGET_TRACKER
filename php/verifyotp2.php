<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $enteredOtp = trim($_POST['otp']);

    // Ensure OTP and session data exists
    if (!isset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['signup_data'])) {
        echo "<script>alert('Session expired or invalid access.'); window.location.href='../pages/signup.php';</script>";
        exit();
    }

    $storedOtp = $_SESSION['otp'];
    $expiry = $_SESSION['otp_expiry'];

    // Check for OTP expiration
    if (time() > $expiry) {
        echo "<script>alert('OTP expired. Please sign up again.'); window.location.href='../pages/signup.php';</script>";
        session_destroy();
        exit();
    }

    // Match OTP
    if ($enteredOtp == $storedOtp) {
        $data = $_SESSION['signup_data'];
        $name = $data['name'];
        $phone = $data['phone'];
        $email = $data['email'];
        $password = $data['password']; // already hashed in signup2.php

        // Insert user into database using prepared statement
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now login.'); window.location.href='../pages/login.php';</script>";
        } else {
            echo "<script>alert('Database error: Unable to register.'); window.location.href='../pages/signup.php';</script>";
        }

        $stmt->close();
        session_destroy(); // ✅ Clean up after success
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.history.back();</script>";
    }
} else {
    echo "Invalid request method.";
}
?>