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
        $mail->Subject = 'Email Verification Code';
        $mail->Body    = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Account Verification</h2>
                <p>Hello {$firstname},</p>
                <p>Your verification code is: <strong>{$verificationCode}</strong></p>
                <p>Please use this code to verify your account.</p>
                <p>If you didn't create an account, please ignore this email.</p>
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
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$firstname || !$lastname || !$role || !$email || !$username || !$password) {
        sendResponse('error', 'All fields are required!');
    }

    $validRoles = ['Admin', 'User'];
    if (!in_array($role, $validRoles)) {
        sendResponse('error', 'Invalid role selected!');
    }

    $verificationCode = generateVerificationCode();
    $status = 'Pending';

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        sendResponse('error', 'Username or email already exists!');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, middle_name, role, email, username, password_hashed, verification_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstname, $lastname, $middlename, $role, $email, $username, $hashedPassword, $verificationCode, $status);

    if ($stmt->execute()) {
        if (sendVerificationEmail($email, $firstname, $verificationCode)) {
            sendResponse('success', 'Registration successful! Please check your email for verification code.');
        } else {
            sendResponse('warning', 'Account created with pending status but verification email could not be sent. Please contact support.');
        }
    } else {
        error_log("Database error: " . $stmt->error);
        sendResponse('error', 'Error registering user!');
    }

    $stmt->close();
    $conn->close();
} else {
    sendResponse('error', 'Invalid request!');
}
?>