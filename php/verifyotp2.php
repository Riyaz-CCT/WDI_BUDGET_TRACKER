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
        header("Location: ../pages/resetpassword.php");
        exit();
    }

    if (!isset($_SESSION['signup_data'], $_SESSION['otp_expiry']) || time() > $_SESSION['otp_expiry']) {
        echo "<script>alert('OTP expired. Please sign up again.'); window.location.href='../pages/signup.php';</script>";
        session_destroy();
        exit();
    }

    $data = $_SESSION['signup_data'];
    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $password = $data['password'];

    // ✅ Check if email or phone already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Email or Phone already exists. Try logging in.'); window.location.href='../pages/signup.php';</script>";
        $checkStmt->close();
        exit();
    }
    $checkStmt->close();

    // ✅ Proceed with registration
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! You can now login.'); window.location.href='../pages/login.php';</script>";
    } else {
        echo "<script>alert('Signup failed. Try again.'); window.location.href='../pages/signup.php';</script>";
    }

    $stmt->close();
    session_destroy();
} else {
    echo "Invalid request.";
}
?>
