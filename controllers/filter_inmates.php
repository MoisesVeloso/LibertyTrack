<?php
require_once 'db_conn.php';

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$search_query = $_POST['search_query'];

$sql = "SELECT id, CONCAT(firstname, ' ', lastname) AS name, case_number, date_admitted 
        FROM inmates 
        WHERE (date_admitted BETWEEN ? AND ? OR ? = '') 
        AND (case_number LIKE ? OR ? = '')
        ORDER BY date_admitted DESC";

$stmt = $conn->prepare($sql);
$search_param = '%' . $search_query . '%';
$stmt->bind_param("sssss", $start_date, $end_date, $start_date, $search_param, $search_query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['case_number']}</td>";
        echo "<td>{$row['date_admitted']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No inmates found for the selected criteria.</td></tr>";
}

$stmt->close();
$conn->close();