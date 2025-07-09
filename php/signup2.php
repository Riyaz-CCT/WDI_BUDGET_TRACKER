<?php
session_start();
include '../php/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name       = trim($_POST['name']);
    $phone      = trim($_POST['phone']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Server-side required field check
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($repassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Basic phone validation
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<script>alert('Invalid phone number format. Must be 10 digits.'); window.history.back();</script>";
        exit();
    }

    // Check if passwords match
    if ($password !== $repassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists (using prepared statement)
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo "<script>alert('Email already registered. Try logging in.'); window.location.href='../pages/login.php';</script>";
        $checkQuery->close();
        exit();
    }

    $checkQuery->close();

    $hashed_password = $password; // Store password as plain text
    // // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user (prepared statement)
    $insertQuery = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
    $insertQuery->bind_param("ssss", $name, $phone, $email, $hashed_password);


    if ($insertQuery->execute()) {
        // Auto-login after signup (optional)
        $_SESSION['user_id'] = $conn->insert_id;
        echo "<script>alert('Signup successful!'); window.location.href='../php/dashboard.php';</script>";
    } else {
        echo "Error: " . $insertQuery->error;
    }

    $insertQuery->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>