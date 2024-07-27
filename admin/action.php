<?php

include_once "../connection.php";

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
if(isset($_POST["action"])) {
  $base_path = 'C:/Users/kaoutar iabakriman/Documents/ONDA'; // Set your base path here
  if ($_POST["action"] == "fetch") {
    function id_dir($path) {
        return is_dir($path);
    }

    $folder = array_filter(glob($base_path . '/*'), 'id_dir');
    $output = '
    <table class="table table-bordered table-striped">
    <tr>
        <th>Folder Name</th>
        <th>Total File</th>
        <th>Last Updated</th>
        <th>Update</th>
        <th>Delete</th>
        <th>Upload File</th>
        <th>View Uploaded File</th>
    </tr>
    ';
    if (count($folder) > 0) {
        foreach ($folder as $name) {
            $last_updated = date("F d, Y H:i:s", filemtime($name));
            $output .= '
            <tr>
                <td>' . basename($name) . '</td>
                <td>' . (count(scandir($name)) - 2) . '</td>
                <td>' . $last_updated . '</td>
                <td>
    <button type="button" name="update" data-name="' . basename($name) . '" class="update btn btn-xs">
        <i class="fa-solid fa-file-pen" style="color: #007bff;"></i>
    </button>
</td>
<td>
    <button type="button" name="delete" data-name="' . basename($name) . '" class="delete btn btn-xs">
        <i class="fa-solid fa-trash-can" style="color: #dc3545;"></i>
    </button>
</td>
<td>
    <button type="button" name="upload" data-name="' . basename($name) . '" class="upload btn btn-xs">
        <i class="fa-solid fa-upload" style="color: #28a745;"></i>
    </button>
</td>
<td>
    <button type="button" name="view_files" data-name="' . basename($name) . '" class="view_files btn btn-xs">
        <i class="fa-solid fa-eye" style="color: #17a2b8;"></i>
    </button>
</td>

        
            </tr>
            ';
        }
    } else {
        $output .= '
        <tr>
            <td colspan="8">No Folder Found</td> 
        </tr>
        ';
    }
    $output .= '</table>';
    echo $output;
}


  if ($_POST["action"] == "create") {
    $folder_name = $base_path . '/' . $_POST["folder_name"];
    if (!file_exists($folder_name)) {
        if (mkdir($folder_name, 0777, true)) {
            logHistory('admin', "Created Folder: " . basename($folder_name));
            echo 'Folder Created';
        } else {
            echo 'Folder Creation Failed: ' . error_get_last()['message'];
        }
    }        
}

if ($_POST["action"] == "change") {
    $old_name = $base_path . '/' . $_POST["old_name"];
    $new_name = $base_path . '/' . $_POST["folder_name"];

    if (!file_exists($new_name)) {
        if (rename($old_name, $new_name)) {
            // Update last modified time
            touch($new_name);
            // Log the update into history
            logHistory('admin', "Updated Folder Name: " . basename($old_name) . " to " . basename($new_name));
            echo 'Folder Name Changed';
        } else {
            echo 'Error renaming folder';
        }
    } else {
        echo 'Folder already exists';
    }
}

  if($_POST["action"] == "delete") {
      $folder_name = $base_path . '/' . $_POST["folder_name"];
      $files = scandir($folder_name);
      foreach($files as $file) {
          if($file === '.' or $file === '..') {
              continue;
          } else {
              unlink($folder_name . '/' . $file);
          }
      }

      if (rmdir($folder_name)) {
        // Log the deletion into history
        logHistory('admin', "Deleted Folder: " . basename($folder_name));
        echo 'Folder Deleted';
    } else {
        echo 'Error deleting folder';
    }
}
  }
  

?>