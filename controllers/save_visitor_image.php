<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imageData = $_POST['imageData'];
    $visitorName = $_POST['visitorName'];

    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    $directory = '../visitors/';
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    $visitorName = preg_replace('/[^A-Za-z0-9]/', '', $visitorName);

    $count = 1;
    $filename = $directory . $visitorName . $count . '.png';
    while (file_exists($filename)) {
        $count++;
        $filename = $directory . $visitorName . $count . '.png';
    }

    file_put_contents($filename, $imageData);

    $relativePath = 'visitors/' . $visitorName . $count . '.png';
    echo json_encode(['status' => 'success', 'filename' => $relativePath]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>