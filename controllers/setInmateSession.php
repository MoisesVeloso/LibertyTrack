<?php
session_start();

if (isset($_POST['id'])) {
    $_SESSION['inmate_id'] = $_POST['id'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID not provided']);
}
?>