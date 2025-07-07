<?php
session_start();
include 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = $_POST['name'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Check if passwords match
    if ($password !== $repassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Try logging in.'); window.location.href='login.html';</script>";
        exit();
    }

    // Hash password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insertQuery = "INSERT INTO users (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$hashed_password')";

    if ($conn->query($insertQuery) === TRUE) {
        echo "<script>alert('Signup successful! Please login.'); window.location.href='login.html';</script>";
        exit();
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
