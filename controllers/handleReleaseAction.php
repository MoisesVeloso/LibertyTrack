<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');
session_start();

try {
    require_once 'db_conn.php';

    if (!isset($_SESSION['username'])) {
        throw new Exception('User not authenticated');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $action = $_POST['action'] ?? '';
    $inmateId = $_POST['inmate_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_SESSION['username'];

    if (!in_array($action, ['release', 'decline']) || !$inmateId || !$password) {
        throw new Exception('Invalid parameters provided');
    }

    $stmt = $conn->prepare("SELECT user_id, password_hashed FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($password, $user['password_hashed'])) {
        throw new Exception('Incorrect Password.');
    }

    $userId = $user['user_id'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT id FROM inmate_release_logs WHERE inmate_id = ?");
        $stmt->bind_param("i", $inmateId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $logId = $result->fetch_assoc()['id'];
            
            $newStatus = ($action === 'release') ? 'approved' : 'declined';
            $stmt = $conn->prepare("INSERT INTO pending_releases (inmate_id, user_id, status, processed_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $inmateId, $userId, $newStatus);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to record release action');
            }

            $stmt = $conn->prepare("DELETE FROM inmate_release_logs WHERE id = ?");
            $stmt->bind_param("i", $logId);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete release request log');
            }
        }

        $inmateStatus = ($action === 'release') ? 'released' : 'detained';
        $stmt = $conn->prepare("UPDATE inmates SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $inmateStatus, $inmateId);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update inmate status');
        }

        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => ($action === 'release') ? 
                'Inmate has been successfully released.' : 
                'Release request has been declined.'
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}