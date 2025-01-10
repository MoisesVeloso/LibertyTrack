<?php
include 'db_conn.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visitor_name = $_POST['visitor_name'];
    $inmate_name = $_POST['inmate_name'];
    $relationship = $_POST['relationship'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $time_in = $_POST['time'];
    $image_path = $_POST['image_path']; 

    if (empty($visitor_name) || empty($inmate_name) || empty($relationship) || empty($purpose) || empty($image_path)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required, including the image!']);
        exit();
    }

    $check_inmate = $conn->prepare("SELECT id FROM inmates WHERE CONCAT(firstname, ' ', lastname) = ?");
    $check_inmate->bind_param("s", $inmate_name);
    $check_inmate->execute();
    $result = $check_inmate->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Inmate is not in the system!']);
        exit();
    }
    
    $inmate = $result->fetch_assoc();
    $inmate_id = $inmate['id'];
    $check_inmate->close();

    $stmt = $conn->prepare("INSERT INTO visitors (visitor_name, inmate_id, inmate_name, relationship, purpose, visit_date, visit_time, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssss", $visitor_name, $inmate_id, $inmate_name, $relationship, $purpose, $date, $time_in, $image_path);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Visitor added successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add visitor!']);
    }

    $stmt->close();
    $conn->close();
}