<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // $mail->SMTPDebug = 2; // 🔧 Uncomment for debugging (shows errors)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sephorafernandes.cct@gmail.com';   // ✅ Your Gmail
        $mail->Password   = 'pfsqgsvpphgejhic';                 // ✅ Your App Password (no space)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // ✅ More secure way
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('sephorafernandes.cct@gmail.com', 'FinTrack');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);                                   // ✅ Send HTML email
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);                // ✅ Fallback for plain text email clients

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);        // ✅ Logs detailed error to PHP error log
        return false;
    }
}
