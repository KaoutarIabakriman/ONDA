<?php
require '../connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folder = $_POST['folder'];
    $file = $_POST['file'];
    $reference = $_POST['reference'];

    // Sanitize input
    $folder = $conn->real_escape_string($folder);
    $file = $conn->real_escape_string($file);
    $reference = $conn->real_escape_string($reference);

    // Check if the file reference already exists
    $check_query = "SELECT * FROM file_references WHERE folder = '$folder' AND file = '$file'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Update existing reference
        $update_query = "UPDATE file_references SET reference = '$reference' WHERE folder = '$folder' AND file = '$file'";
        if ($conn->query($update_query) === TRUE) {
            echo 'success';
        } else {
            echo 'Error updating record: ' . $conn->error;
        }
    } else {
        // Insert new reference
        $insert_query = "INSERT INTO file_references (folder, file, reference) VALUES ('$folder', '$file', '$reference')";
        if ($conn->query($insert_query) === TRUE) {
            echo 'success';
        } else {
            echo 'Error inserting record: ' . $conn->error;
        }
    }
}

$conn->close();
?>
