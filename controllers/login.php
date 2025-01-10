<?php
require 'db_conn.php'; 

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$username || !$password) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT password_hashed, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $role);
    $stmt->fetch();
    $stmt->close();

    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        $_SESSION['username'] = $username; 
        $_SESSION['role'] = $role; 
        header("Location: ../home.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: ../index.php");
        exit();
    }

    $conn->close();
} else {
    header("Location: ../index.php?alert=error&message=Invalid request!");
    exit();
}
?>