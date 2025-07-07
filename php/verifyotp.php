<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    // Check if OTP exists in session
    if (!isset($_SESSION['otp'])) {
        echo "Session expired. Please login again.";
        exit();
    }

    $stored_otp = $_SESSION['otp'];

    if ($entered_otp == $stored_otp) {
        // OTP matched — clear OTP from session
        unset($_SESSION['otp']);

        // Optional: you can also store a session flag for logged-in user now
        $_SESSION['loggedin'] = true;

        // Redirect to dashboard or homepage
        header("Location: dashboard.html");
        exit();
    } else {
        // Incorrect OTP
        echo "<script>alert('Incorrect OTP. Please try again.'); window.history.back();</script>";
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
