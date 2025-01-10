<?php
// create_post.php

require 'db_conn.php'; 

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id_query = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$user_id_query->bind_param("s", $username);
$user_id_query->execute();
$user_id_query->bind_result($user_id);
$user_id_query->fetch();
$user_id_query->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_path = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        
        $unique_name = uniqid() . '.' . $extension;
        $full_image_path = $upload_dir . $unique_name;

        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            echo "Upload directory does not exist or is not writable.";
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $full_image_path)) {
                $image_path = 'uploads/' . $unique_name;
                echo "File uploaded successfully.";
            } else {
                echo "Failed to move uploaded file.";
            }
        }
    } else {
        echo "File upload error: " . $_FILES['image']['error'];
    }

    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image_path, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("isss", $user_id, $title, $content, $image_path);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>