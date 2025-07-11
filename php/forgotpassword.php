<?php
session_start();
include('../php/db.php');
include('../php/mail.php'); // ✅ Include mail function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in users table
    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // ✅ Get user details
        $name = $row['name']; // ✅ Extract name

        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_type'] = 'forgot_password';

        // Compose email
        $subject = "FinTrack Pro - OTP for Password Reset";
        $message = "Hi $name,\n\nYour OTP for resetting your password is: $otp\n\nThis OTP is valid for 5 minutes.\n\n- FinTrack Pro Team";

        // Send email
        if (sendMail($email, $subject, $message)) {
            header("Location: ../pages/verifyotp.html");
            exit();
        } else {
            echo "<script>alert('Failed to send OTP. Try again later.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not registered.'); window.history.back();</script>";
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
