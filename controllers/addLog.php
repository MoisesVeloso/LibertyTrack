<?php
require 'db_conn.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_logs.log');

function logMessage($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inmateId = $_POST['inmate_id'] ?? '';
    $activityType = $_POST['activity_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $location = $_POST['location'] ?? '';

    if (empty($inmateId) || empty($activityType) || empty($location)) {
        logMessage("Error: Missing required fields. Inmate ID: $inmateId, Activity Type: $activityType, Location: $location");
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO inmate_logs (inmate_id, activity_type, description, duration, location) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        logMessage("Error: Failed to prepare statement - " . $conn->error);
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
        exit;
    }

    $stmt->bind_param("issss", $inmateId, $activityType, $description, $duration, $location);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        logMessage("Error: Failed to execute statement - " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Failed to add log']);
    }

    $stmt->close();
} else {
    logMessage("Error: Invalid request method");
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
