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

if (isset($_GET['file'])) {
    $file_path = urldecode($_GET['file']);
    if (file_exists($file_path)) {
        $folder_name = basename(dirname($file_path));
logHistory('admin', "Downloaded File: " . basename($file_path) . " from Folder: " . $folder_name);


        // Force download the file
        header('Content-Description: File Transfer');
        header('Content-Type: ' . mime_content_type($file_path));
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo 'File does not exist.';
    }
} else {
    echo 'No file specified.';
}
?>
