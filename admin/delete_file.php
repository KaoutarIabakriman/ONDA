<?php
include_once "../connection.php"; // Ensure this path is correct

// Function to log history
function logHistory($author, $changes) {
    global $conn;
    $query = "INSERT INTO document_history (date_time, author, changes) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $dateTime = date('Y-m-d H:i:s');
    $stmt->bind_param("sss", $dateTime, $author, $changes);

    if (!$stmt->execute()) {
        error_log("Error logging history: " . $stmt->error);
    }
    $stmt->close();
}

if (isset($_POST['file'])) {
    $file_path = urldecode($_POST['file']);
    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            $folder_name = basename(dirname($file_path));
            logHistory('admin', "Downloaded File: " . basename($file_path) . " from Folder: " . $folder_name);
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
