<?php
session_start();
require_once 'db_conn.php';

$data = json_decode(file_get_contents('php://input'), true);
$inmateId = $data['inmateId'];
$status = $data['status'];
$userId = $_SESSION['user_id'];

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("UPDATE inmates SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $inmateId);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO inmate_release_logs (inmate_id, user_id, action) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $inmateId, $userId, $status);
    $stmt->execute();

    $conn->commit();

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?> 