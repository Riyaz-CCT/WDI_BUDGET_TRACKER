<?php
session_start();
include('../php/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Debug: Print email and raw password to console
    echo "<script>console.log('Email: " . $email . "');</script>";
    echo "<script>console.log('Entered Password: " . $password . "');</script>";

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required.'); window.history.back();</script>";
        exit();
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Debug: Print stored hash
        echo "<script>console.log('Stored Hashed Password: " . $row['password'] . "');</script>";

        // Verify password
        if (password_verify($password, $row['password'])) {
            echo "<script>
                console.log('✅ Password matched');
                alert('Login successful! Redirecting...');
            </script>";

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Send OTP (if needed)
            include 'mailer/send_otp.php';

            // Redirect to dashboard
            header("Location: ../pages/dashboard.php");
            exit();
        } else {
            echo "<script>
                console.log('❌ Incorrect password');
                alert('Incorrect password.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            console.log('❌ Email not registered');
            alert('Email not registered.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
