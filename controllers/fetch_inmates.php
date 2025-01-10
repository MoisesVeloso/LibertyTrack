<?php
include 'db_conn.php';

if (isset($_GET['query'])) {
    $search = '%' . $_GET['query'] . '%';
    
    $stmt = $conn->prepare("SELECT id, firstname, lastname 
                           FROM inmates 
                           WHERE (CONCAT(firstname, ' ', lastname) LIKE ? 
                           OR firstname LIKE ? 
                           OR lastname LIKE ?) 
                           AND status = 'detained'");
    
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $inmates = array();
    while ($row = $result->fetch_assoc()) {
        $inmates[] = array(
            'id' => $row['id'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'fullname' => $row['firstname'] . ' ' . $row['lastname']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($inmates);
    
    $stmt->close();
    $conn->close();
}
?>