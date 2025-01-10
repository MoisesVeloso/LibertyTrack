<?php
require 'db_conn.php';

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$query = "SELECT MONTH(date_admitted) as month, COUNT(*) as count 
          FROM inmates 
          WHERE YEAR(date_admitted) = ? 
          GROUP BY MONTH(date_admitted)
          ORDER BY month";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$monthlyData = array_fill(0, 12, 0);

while ($row = $result->fetch_assoc()) {
    $monthlyData[$row['month'] - 1] = intval($row['count']);
}

echo json_encode([
    'values' => $monthlyData
]);

$stmt->close();
$conn->close(); 