<?php
session_start();
include 'db_conn.php';

$data = json_decode(file_get_contents('php://input'), true);
$response = ['status' => 'error', 'message' => ''];

if (!isset($_SESSION['username'])) {
    $response['message'] = 'Not authenticated';
    echo json_encode($response);
    exit;
}

$currentPassword = $data['currentPassword'];
$newPassword = $data['newPassword'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT password_hashed FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($currentPassword, $user['password_hashed'])) {
    $response['message'] = 'Current password is incorrect';
    echo json_encode($response);
    exit;
}

if (strlen($newPassword) < 8 ||
    !preg_match("/[A-Z]/", $newPassword) ||
    !preg_match("/[a-z]/", $newPassword) ||
    !preg_match("/[0-9]/", $newPassword) ||
    !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $newPassword)) {
    $response['message'] = 'New password does not meet requirements';
    echo json_encode($response);
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password_hashed = ?, updated_at = NOW() WHERE username = ?");
$stmt->bind_param("ss", $hashedPassword, $username);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Password changed successfully';
} else {
    $response['message'] = 'Error changing password';
}

echo json_encode($response);
