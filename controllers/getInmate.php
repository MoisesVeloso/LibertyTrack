<?php
   include 'db_conn.php'; 

   if (isset($_GET['id'])) {
       $id = intval($_GET['id']); 
       $stmt = $conn->prepare("SELECT * FROM inmates WHERE id = ?");
       $stmt->bind_param("i", $id);
       $stmt->execute();
       $result = $stmt->get_result();

       if ($result->num_rows > 0) {
           $inmate = $result->fetch_assoc();
           echo json_encode($inmate);
       } else {
           echo json_encode(['error' => 'No inmate found']);
       }
   } else {
       echo json_encode(['error' => 'Invalid ID']);
   }
   ?>