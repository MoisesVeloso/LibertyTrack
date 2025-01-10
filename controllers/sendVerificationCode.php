<?php

session_start();
require 'db_conn.php';
require '../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendResponse($alert, $message) {
    header('Content-Type: application/json');
    echo json_encode(['alert' => $alert, 'message' => $message]);
    exit();
}

function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendVerification($email, $firstname, $subject, $body) {
    $config = require 'credentials.php';
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['email_username'];
        $mail->Password   = $config['email_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('psmoriones@gmail.com', 'LibertyTrack');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!$email) {
        sendResponse('error', 'Email is required!');
    }

    $stmt = $conn->prepare("SELECT first_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($firstname);
    $stmt->fetch();
    $stmt->close();

    if (!$firstname) {
        sendResponse('error', 'Email not found!');
    }

    $_SESSION['email'] = $email;

    $verificationCode = generateVerificationCode();

    $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
    $stmt->bind_param("ss", $verificationCode, $email);

    if ($stmt->execute()) {
        $subject = 'Reset Password Code';
        $body = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Verification Code</h2>
                <p>Hello {$firstname},</p>
                <p>Your verification code is: <strong>{$verificationCode}</strong></p>
                <p>Please use this code to reset your account.</p>
                <p>If you didn't request this, please ignore this email.</p>
            </body>
            </html>
        ";

        if (sendVerification($email, $firstname, $subject, $body)) {
            sendResponse('success', 'Verification code sent! Please check your email.');
        } else {
            sendResponse('warning', 'Verification code could not be sent. Please contact support.');
        }
    } else {
        error_log("Database error: " . $stmt->error);
        sendResponse('error', 'Error updating verification code!');
    }

    $stmt->close();
    $conn->close();
} else {
    sendResponse('error', 'Invalid request!');
}
?> 