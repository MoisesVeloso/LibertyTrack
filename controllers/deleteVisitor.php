<?php
require_once 'db_conn.php';

$data = json_decode(file_get_contents('php://input'), true);
$visitor_id = $data['visitor_id'];
$image_path = $data['image_path'];

if (!empty($image_path) && file_exists("../" . $image_path)) {
    unlink("../" . $image_path);
}

$stmt = $conn->prepare("DELETE FROM visitors WHERE id = ?");
$stmt->bind_param("i", $visitor_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Visitor deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete visitor']);
}

$stmt->close();
$conn->close();
?>
