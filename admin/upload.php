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
    $file_name = basename($_FILES["upload_file"]["name"]);
    $reference = trim($_POST['file_reference']); // Get the reference
    $path = 'C:/Users/kaoutar iabakriman/Documents/ONDA/' . $folder_name . '/' . $file_name;

    if (is_dir(dirname($path))) {
        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path)) {
            // Log the file upload in document history
            logHistory('admin', "Uploaded File: " . $file_name . " to Folder: " . $folder_name);

            // Insert or update the file reference in the database
            $file_name_escaped = $conn->real_escape_string($file_name);
            $folder_name_escaped = $conn->real_escape_string($folder_name);
            $reference_escaped = $conn->real_escape_string($reference);

            // Check if the file reference already exists
            $check_query = "SELECT * FROM file_references WHERE folder = '$folder_name_escaped' AND file = '$file_name_escaped'";
            $check_result = $conn->query($check_query);

            if ($check_result->num_rows > 0) {
                // Update existing reference
                $update_query = "UPDATE file_references SET reference = '$reference_escaped' WHERE folder = '$folder_name_escaped' AND file = '$file_name_escaped'";
                if ($conn->query($update_query) === TRUE) {
                    echo "File Uploaded Successfully and Reference Updated";
                } else {
                    echo "Error updating reference: " . $conn->error;
                }
            } else {
                // Insert new reference
                $insert_query = "INSERT INTO file_references (folder, file, reference) VALUES ('$folder_name_escaped', '$file_name_escaped', '$reference_escaped')";
                if ($conn->query($insert_query) === TRUE) {
                    echo "File Uploaded Successfully and Reference Added";
                } else {
                    echo "Error inserting reference: " . $conn->error;
                }
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
?>
