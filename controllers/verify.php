<?php

header('Content-Type: application/json');

require 'db_conn.php'; 

ini_set('log_errors', 1);
ini_set('error_log', '../logs/php-error.log');

function logMessage($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    logMessage("Received POST request");
    $inmateId = $_POST['inmate_id'] ?? '';
    
    logMessage("POST data: " . print_r($_POST, true));
    logMessage("FILES data: " . print_r($_FILES, true));

    if (empty($inmateId) || !isset($_FILES['image'])) {
        logMessage("Error: No image or inmate ID received");
        echo json_encode(['success' => false, 'error' => 'No image or inmate ID received']);
        exit;
    }

    $tempDir = '../temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $tempImagePath = $tempDir . uniqid() . '.png';
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $tempImagePath)) {
        logMessage("Error: Failed to save temporary image. Upload error code: " . $_FILES['image']['error']);
        echo json_encode(['success' => false, 'error' => 'Failed to save temporary image']);
        exit;
    }

    $stmt = $conn->prepare("SELECT image_data_path FROM inmates WHERE id = ?");
    $stmt->bind_param("i", $inmateId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageDataPath = $row['image_data_path'];
    } else {

        unlink($tempImagePath);
        logMessage("Error: No inmate found with ID: " . $inmateId);
        echo json_encode(['success' => false, 'error' => 'No inmate found with the given ID']);
        exit;
    }

    $imageDataPath = escapeshellarg($imageDataPath);
    $tempImagePath = escapeshellarg($tempImagePath);
    $command = "python ../src/facial_recognition_script.py $imageDataPath $tempImagePath 2>&1";  

    logMessage("Executing command: " . $command);
    $output = shell_exec($command);
    logMessage("Python script output: " . $output);

    if (file_exists($tempImagePath)) {
        unlink($tempImagePath);
        logMessage("Temporary file deleted: " . $tempImagePath);
    }

    if (strpos($output, 'Match Found') !== false) {
        logMessage("Verification successful");
        echo json_encode(['success' => true]);
    } else {
        logMessage("Verification failed: " . $output);
        echo json_encode(['success' => false, 'error' => 'Facial verification failed: ' . $output]);
    }
} else {
    logMessage("Error: Invalid request method");
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
