<?php
session_start();
include 'db.php';
include 'mail.php';

// Get user email from session
$email = $_SESSION['email'] ?? null;

if (!$email) {
    die("Unauthorized access. Please log in.");
}

// Generate 6-digit OTP
$otp = rand(100000, 999999);

// Store OTP and timestamp in session
$_SESSION['delete_otp'] = $otp;
$_SESSION['otp_generated_time'] = time();

// Prepare email content
$subject = "FinTrack | OTP for Account Deletion";
$message = "
    <p>Hi,</p>
    <p>You requested to delete your <b>FinTrack</b> account. Please use the OTP below to confirm the action:</p>
    <h2 style='letter-spacing: 2px; font-size: 24px;'>$otp</h2>
    <p>This OTP is valid for <b>5 minutes</b>.</p>
    <p>If you did not request this, you can safely ignore this email.</p>
    <br>
    <p>Regards,<br><strong>Team FinTrack</strong></p>
";

// Attempt to send the email
if (sendMail($email, $subject, $message)) {
    header("Location: ../pages/verify_delete_otp.html");
    exit();
} else {
    echo "⚠️ Failed to send OTP. Please check your email or try again later.";
}
?>