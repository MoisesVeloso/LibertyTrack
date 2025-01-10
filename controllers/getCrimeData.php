<?php
require 'db_conn.php';

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$query = "SELECT 
            case_detail,
            COUNT(*) as count
          FROM inmates 
          WHERE YEAR(date_admitted) = ?
          GROUP BY case_detail
          ORDER BY count DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['case_detail'];
    $values[] = (int)$row['count'];
}

$response = [
    'labels' => $labels,
    'values' => $values
];

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
