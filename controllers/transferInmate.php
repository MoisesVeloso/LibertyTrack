<?php
session_start();
require 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request or not logged in']);
    exit;
}

$username = $_SESSION['username'];
$userStmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

$userId = $userResult->fetch_assoc()['user_id'];
$userStmt->close();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['inmateId']) || !isset($data['location'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
    exit;
}

$inmateId = $data['inmateId'];
$location = $data['location'];

try {
    $conn->begin_transaction();

    $checkStmt = $conn->prepare("SELECT status FROM inmates WHERE id = ?");
    $checkStmt->bind_param("i", $inmateId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Inmate not found');
    }
    
    $inmateStatus = $result->fetch_assoc()['status'];
    if ($inmateStatus === 'transferred') {
        throw new Exception('Inmate has already been transferred');
    }
    
    $insertStmt = $conn->prepare("INSERT INTO inmate_transfers (inmate_id, transfer_location, user_id) VALUES (?, ?, ?)");
    $insertStmt->bind_param("isi", $inmateId, $location, $userId);
    
    if (!$insertStmt->execute()) {
        throw new Exception('Failed to insert transfer record: ' . $insertStmt->error);
    }

    $updateStmt = $conn->prepare("UPDATE inmates SET status = 'transferred' WHERE id = ?");
    $updateStmt->bind_param("i", $inmateId);
    
    if (!$updateStmt->execute()) {
        throw new Exception('Failed to update inmate status: ' . $updateStmt->error);
    }

    $conn->commit();
    
    echo json_encode(['status' => 'success', 'message' => 'Inmate transferred successfully']);

} catch (Exception $e) {
    $conn->rollback();
    error_log('Transfer Error: ' . $e->getMessage()); 
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($checkStmt)) $checkStmt->close();
    if (isset($insertStmt)) $insertStmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    $conn->close();
}
?>
