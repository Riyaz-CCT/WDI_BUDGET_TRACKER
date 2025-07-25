<?php
session_start();
include 'db.php';

// Check required session values
$user_email = $_SESSION['email'] ?? null;
$entered_otp = $_POST['otp'] ?? null;
$actual_otp = $_SESSION['delete_otp'] ?? null;
$otp_time = $_SESSION['otp_generated_time'] ?? 0;

// OTP is valid for 5 minutes
if (!$user_email || !$actual_otp || (time() - $otp_time > 300)) {
    $_SESSION['otp_error'] = "OTP expired or invalid session.";
    header("Location: ../pages/verify_delete_otp.html");
    exit();
}

// Match entered OTP
if ($entered_otp == $actual_otp) {
    // Delete the user from database
    $stmt = $conn->prepare("DELETE FROM Users WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();

    // Check if user was deleted
    if ($stmt->affected_rows > 0) {
        // Clear OTP session data and destroy session
        unset($_SESSION['delete_otp'], $_SESSION['otp_generated_time']);
        session_destroy();

        echo "<script>
            alert('✅ Your account has been successfully deleted.');
            window.location.href = '../pages/signup.php';
        </script>";
        exit();
    } else {
        $stmt->close();
        $_SESSION['otp_error'] = "User deletion failed. Try again.";
        header("Location: ../pages/verify_delete_otp.html");
        exit();
    }

    $stmt->close();
} else {
    $_SESSION['otp_error'] = "Invalid OTP. Please try again.";
    header("Location: ../pages/verify_delete_otp.html");
    exit();
}
?>
