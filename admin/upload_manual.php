<?php
include_once "../connection.php";

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

if ($_FILES["upload_file"]["name"] != '') {
    $folder_name = basename($_POST["hidden_folder_name"]);
    // Save uploaded file as manual.pdf
    $path = 'C:/Users/kaoutar iabakriman/Documents/ONDA/' . $folder_name . '/manual_' . $folder_name . '.pdf'; 
    $reference = $_POST['file_reference'];

    if (is_dir(dirname($path))) {
        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path)) {
            // Log the file upload in document history
            logHistory('admin', "Uploaded File: manual.pdf to Folder: " . $folder_name);
            
            // Insert or update the file reference in the database
            $sql = "INSERT INTO file_references (file, reference, folder) VALUES ('manual.pdf', '$reference', '$folder_name') ON DUPLICATE KEY UPDATE reference='$reference'";
            if (mysqli_query($conn, $sql)) {
                echo "File Uploaded Successfully";
            } else {
                echo "Error updating reference: " . mysqli_error($conn);
            }
        } else {
            echo "There was an error uploading the file";
        }
    } else {
        echo "Target folder does not exist";
    }
} else {
    echo "Please select a file to upload";
}

mysqli_close($conn);
?>
