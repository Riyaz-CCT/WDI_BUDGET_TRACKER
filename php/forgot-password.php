<?php
session_start();
include 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in users table
    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Email exists — generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_type'] = 'forgot_password'; // Optional: to distinguish this OTP

        // Send OTP via email
        include 'mailer/send_otp.php';

        // Redirect to verify OTP page
        header("Location: verifyotp.html");
        exit();
    } else {
        echo "<script>alert('Email not registered.'); window.history.back();</script>";
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
