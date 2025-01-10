<?php
require 'db_conn.php';

function sendResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventTitle = $_POST['event_title'];
    $eventDescription = $_POST['event_description'];
    $eventDate = $_POST['event_date'];
    $eventTime = $_POST['event_time'];

    if (empty($eventTitle) || empty($eventDescription) || empty($eventDate) || empty($eventTime)) {
        sendResponse('error', 'All fields are required.');
    }

    $stmt = $conn->prepare("INSERT INTO events (title, description, date, time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $eventTitle, $eventDescription, $eventDate, $eventTime);

    if ($stmt->execute()) {
        sendResponse('success', 'Event created successfully.');
    } else {
        sendResponse('error', 'There was an error creating the event.');
    }

    $stmt->close();
    $conn->close();
} else {
    sendResponse('error', 'Invalid request method.');
}
?>