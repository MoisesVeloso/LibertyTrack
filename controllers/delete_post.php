<?php
require 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];

    $stmt = $conn->prepare("SELECT image_path FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        if ($image_path) {
            $file_path = __DIR__ . '/../' . $image_path; 
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        echo "Post and associated image deleted successfully.";
    } else {
        echo "Error deleting post: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?> 