<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user_id = $_POST['user_id'];
        $action = $_POST['action'];
        
        $new_status = ($action === 'authorize') ? 'Verified' : 'Suspended';
        
        $query = "UPDATE users SET status = ?, updated_at = NOW() WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$new_status, $user_id]);
        
        $_SESSION['success'] = "User status updated successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating user status: " . $e->getMessage();
    }
    
    header('Location: ../dashboard.php');
    exit;
} 