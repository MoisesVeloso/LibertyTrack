<?php
require 'db_conn.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No inmate ID provided']);
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM logs WHERE inmate_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);
?>