<?php
session_start();
include '../php/db.php';
include '../php/mail.php'; // ✅ Custom mail function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = trim($_POST['name']);
    $phone      = trim($_POST['phone']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    // 🚨 Basic Validation
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($repassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // ✅ Email Format Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    // ✅ Phone Format Validation
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<script>alert('Invalid phone number. Must be 10 digits.'); window.history.back();</script>";
        exit();
    }

    // ✅ Password Match Check
    if ($password !== $repassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // 🔐 Strong Password Check (Optional, comment out if not needed)
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>";
        exit();
    }

    // ✅ Check if user already exists
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo "<script>alert('Email already registered. Try logging in.'); window.location.href='../pages/login.html';</script>";
        $checkQuery->close();
        exit();
    }
    $checkQuery->close();

    // ✅ Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Generate and store OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // 5 minutes

    $_SESSION['signup_data'] = [
        'name'     => $name,
        'phone'    => $phone,
        'email'    => $email,
        'password' => $hashed_password
    ];

    // ✅ Send the OTP email
    $subject = "FinTrack Pro - OTP Verification";
    $message = "Hi $name,\n\nYour OTP for FinTrack signup is: $otp\n\nThis OTP is valid for 5 minutes.\n\n- FinTrack Pro Team";

    if (sendMail($email, $subject, $message)) {
        header("Location: ../pages/verify_otp.html");
        exit();
    } else {
        echo "<script>alert('Failed to send OTP. Try again later.'); window.history.back();</script>";
    }
} else {
    echo "Invalid request.";
}
?>
