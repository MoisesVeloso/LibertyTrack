<?php
session_start();
require 'db_conn.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $inmateId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $userStmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    if (!$userStmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
        exit();
    }
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $userId = $user['user_id'];

        $stmt = $conn->prepare("UPDATE inmates SET status = 'reviewing' WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
            exit();
        }
        $stmt->bind_param("i", $inmateId);

        if ($stmt->execute()) {
            $action = "Status changed to reviewing";
            $actionStmt = $conn->prepare("INSERT INTO inmate_actions (inmate_id, user_id, action) VALUES (?, ?, ?)");
            if (!$actionStmt) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
                exit();
            }
            $actionStmt->bind_param("iis", $inmateId, $userId, $action);
            $actionStmt->execute();
            $actionStmt->close();

            // Insert into inmate_release_logs
            $releaseLogStmt = $conn->prepare("INSERT INTO inmate_release_logs (inmate_id, user_id, action) VALUES (?, ?, 'reviewing')");
            if (!$releaseLogStmt) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
                exit();
            }
            $releaseLogStmt->bind_param("ii", $inmateId, $userId);
            $releaseLogStmt->execute();
            $releaseLogStmt->close();

            echo json_encode(['status' => 'success', 'message' => 'Inmate status updated to reviewing']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update inmate status']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    $userStmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>