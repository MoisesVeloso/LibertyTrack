<?php
require 'db_conn.php';

function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT image_path FROM inmates WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $imagePath = $row['image_path'];

    $directoryPath = dirname($imagePath);

    $query = "DELETE FROM inmates WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if (is_dir($directoryPath)) {
            deleteDirectory($directoryPath);
        }
        header("Location: ../inmates.php?alert=success&message=Inmate and associated files deleted successfully!");
        exit();
    } else {
        header("Location: ../inmates.php?alert=error&message=Failed to delete inmate!");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../inmates.php?alert=error&message=No Inmate ID Provided");
    exit();
}
?>