<?php
session_start(); // Ensure the session is started
include_once "../connection.php"; // Ensure this path is correct



// Function to log history
function logHistory($changes) {
    global $conn;
    $author = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
    $query = "INSERT INTO document_history (date_time, author, changes) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $dateTime = date('Y-m-d H:i:s');
    $stmt->bind_param("sss", $dateTime, $author, $changes);
    if (!$stmt->execute()) {
        error_log("Error logging history: " . $stmt->error);
    }
    $stmt->close();
}

// Debugging: Log the current session variable
error_log("Current User: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'Not set'));

if (isset($_GET['file'])) {
    $file_path = urldecode($_GET['file']);
    if (file_exists($file_path)) {
        // Log the file download in document history
        $folder_name = basename(dirname($file_path));
        logHistory("Downloaded File: " . basename($file_path) . " from Folder: " . $folder_name);

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
