<?php
session_start();
include('../php/db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

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

        echo "Entered: $password<br>Stored: {$row['password']}<br>";


        // Verify password hash
        // if (password_verify($password, $row['password'])) 
        if ($password === $row['password']) // Direct comparison
        {


            echo "<script>console.log('password1=" . $password . ", password2=" . $row['password'] . "');
    alert('password1=" . $password . ", password2=" . $row['password'] . "');
</script> ";
          //  echo "<script>console.log('password1=" . $password . ", password2=" . $row['password'] . "');</script>";
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Send OTP (include email sender script)
            include 'mailer/send_otp.php';

            // Redirect to OTP verification page
            header("Location: verifyotp.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not registered.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
