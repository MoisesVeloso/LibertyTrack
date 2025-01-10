<?php

session_start();
require 'db_conn.php';
require '../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendVerificationEmail($email, $firstname, $verificationCode) {
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
        $mail->Subject = 'Resend Verification Code';
        $mail->Body    = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Account Verification</h2>
                <p>Hello {$firstname},</p>
                <p>Your new verification code is: <strong>{$verificationCode}</strong></p>
                <p>Please use this code to verify your account.</p>
                <p>If you didn't request this, please ignore this email.</p>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'] ?? null;

    if (!$username) {
        sendResponse('error', 'User not logged in!');
    }

    $stmt = $conn->prepare("SELECT first_name, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($firstname, $email);
    $stmt->fetch();
    $stmt->close();

    if (!$email) {
        sendResponse('error', 'User not found!');
    }

    $verificationCode = generateVerificationCode();

    $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE username = ?");
    $stmt->bind_param("ss", $verificationCode, $username);

    if ($stmt->execute()) {
        if (sendVerificationEmail($email, $firstname, $verificationCode)) {
            sendResponse('success', 'Verification code resent successfully!');
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