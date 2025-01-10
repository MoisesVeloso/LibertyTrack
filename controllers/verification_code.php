<?php
session_start();
require 'db_conn.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$username = $_SESSION['username'];

$enteredCode = implode('', $_POST['code']);

$stmt = $conn->prepare("SELECT verification_code FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($storedCode);
$stmt->fetch();
$stmt->close();

if ($enteredCode === $storedCode) {
    $newStatus = 'Reviewing';
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE username = ?");
    $stmt->bind_param("ss", $newStatus, $username);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Verification successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating account status. Please try again.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect verification code. Please try again.']);
}

$conn->close();
?> 