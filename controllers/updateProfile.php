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

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$middleName = $data['middleName'];
$email = $data['email'];
$username = $data['username'];
$currentUsername = $_SESSION['username'];

$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND username != ?");
$stmt->bind_param("ss", $email, $currentUsername);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $response['message'] = 'Email already in use';
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, middle_name = ?, email = ?, username = ?, updated_at = NOW() WHERE username = ?");
$stmt->bind_param("ssssss", $firstName, $lastName, $middleName, $email, $username, $currentUsername);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $response['status'] = 'success';
    $response['message'] = 'Profile updated successfully';
} else {
    $response['message'] = 'Error updating profile';
}

echo json_encode($response);
