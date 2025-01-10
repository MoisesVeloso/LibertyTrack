<?php
require_once 'db_conn.php';

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$search_query = $_POST['search_query']; // Get the search query

$sql = "SELECT id, visitor_name, inmate_name, relationship, purpose, visit_date, visit_time 
        FROM visitors 
        WHERE (visit_date BETWEEN ? AND ? OR ? = '') 
        AND (visitor_name LIKE ? OR inmate_name LIKE ? OR ? = '')
        ORDER BY visit_date DESC, visit_time DESC";

$stmt = $conn->prepare($sql);
$search_param = '%' . $search_query . '%';
$stmt->bind_param("ssssss", $start_date, $end_date, $start_date, $search_param, $search_param, $search_query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['visitor_name']}</td>";
        echo "<td>{$row['inmate_name']}</td>";
        echo "<td>{$row['relationship']}</td>";
        echo "<td>{$row['purpose']}</td>";
        echo "<td>{$row['visit_date']}</td>";
        echo "<td>{$row['visit_time']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No visitors found for the selected criteria.</td></tr>";
}

$stmt->close();
$conn->close();