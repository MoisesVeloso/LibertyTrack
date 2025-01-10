<?php
session_start();
require 'db_conn.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        echo json_encode(['alert' => 'error', 'message' => 'Session expired. Please try again.']);
        exit();
    }

    $verificationCode = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_STRING);
    $newPassword = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

    if (!$verificationCode || !$newPassword) {
        echo json_encode(['alert' => 'error', 'message' => 'All fields are required!']);
        exit();
    }

    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode(['alert' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($storedCode);
    $stmt->fetch();
    $stmt->close();

    if ($storedCode === $verificationCode) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password_hashed = ?, verification_code = NULL WHERE email = ?");
        if (!$stmt) {
            echo json_encode(['alert' => 'error', 'message' => 'Database error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            echo json_encode(['alert' => 'success', 'message' => 'Password updated successfully!']);
            unset($_SESSION['email']);
        } else {
            echo json_encode(['alert' => 'error', 'message' => 'Error updating password!']);
        }

        $stmt->close();
    } else {
        echo json_encode(['alert' => 'error', 'message' => 'Invalid verification code!']);
    }

    $conn->close();
    exit();
}
?>
