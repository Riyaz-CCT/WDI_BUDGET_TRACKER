<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = trim($_POST['otp']);

    if (!isset($_SESSION['otp'], $_SESSION['otp_type'], $_SESSION['email'])) {
        echo "<script>alert('Session expired or invalid access.'); window.location.href='../pages/login.php';</script>";
        exit();
    }

    if ($enteredOtp != $_SESSION['otp']) {
        echo "<script>alert('Invalid OTP. Please try again.'); window.history.back();</script>";
        exit();
    }

    // OTP matched
    if ($_SESSION['otp_type'] === 'forgot_password') {
        // Forward to password reset form
        header("Location: ../pages/resetpassword.html");
        exit();
    }

    // Otherwise it's signup flow
    if (!isset($_SESSION['signup_data'], $_SESSION['otp_expiry']) || time() > $_SESSION['otp_expiry']) {
        echo "<script>alert('OTP expired. Please sign up again.'); window.location.href='../pages/signup.html';</script>";
        session_destroy();
        exit();
    }

    $data = $_SESSION['signup_data'];
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['name'], $data['phone'], $data['email'], $data['password']);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! You can now login.'); window.location.href='../pages/login.php';</script>";
    } else {
        echo "<script>alert('Signup failed. Try again.'); window.location.href='../pages/signup.html';</script>";
    }

    $stmt->close();
    session_destroy();
} else {
    echo "Invalid request.";
}
?>