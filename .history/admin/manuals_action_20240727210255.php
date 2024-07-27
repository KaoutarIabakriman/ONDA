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

if (isset($_POST["action"])) {
    $base_path = 'C:/Users/kaoutar iabakriman/Documents/ONDA'; // Set your base path here

    if(isset($_POST["action"])) {
        $base_path = 'C:/Users/kaoutar iabakriman/Documents/ONDA'; // Set your base path here
        if ($_POST["action"] == "fetch") {
          
          $folder = array_filter(glob($base_path . '/*'), 'is_dir');
          $output = '
          <table class="table table-bordered table-striped">
          <tr>
              <th>Folder Name</th>
              <th>Upload Manual</th>
              <th>Last Updated</th>
              <th>Delete</th>
              <th>Download</th>
          </tr>
          ';
          if (count($folder) > 0) {
              foreach ($folder as $name) {
                  $last_updated = date("F d, Y H:i:s", filemtime($name));
                  $folder_name = basename($name);
                  // Construct the file path for the uploaded manual
                  $file_path = $base_path . '/' . $folder_name . '/manual_' . $folder_name . '.pdf'; 
                  // Create a download link if the file exists
                  $download_link = file_exists($file_path) ? '<a href="download.php?file=' . urlencode($file_path) . '" class="btn btn-xs" style="color: #007bff;"><i class="fa fa-download"></i></a>' : 'No File';
                  
                  $output .= '
                  <tr>
                  
                      <td>' . $folder_name . '</td>
                      <td>
                          <button type="button" name="upload" data-name="' . $folder_name . '" class="upload btn btn-xs">
                              <i class="fa-solid fa-upload" style="color: #28a745;"></i>
                          </button>
                      </td>
                      <td>' . $last_updated . '</td>
                      <td>
                          <button type="button" name="delete" data-name="' . $folder_name . '" class="delete btn btn-xs">
                              <i class="fa-solid fa-trash-can" style="color: #dc3545;"></i>
                          </button>
                      </td>
                      <td>' . $download_link . '</td>
                  </tr>
                  ';
              }
          } else {
              $output .= '
              <tr>
                  <td colspan="5">No Folder Found</td>
              </tr>
              ';
          }
          $output .= '</table>';
          echo $output;
      }
    }      
    if ($_POST["action"] == "delete") {
        $folder_name = $base_path . '/' . $_POST["folder_name"];
        $files = scandir($folder_name);
        foreach ($files as $file) {
            if ($file === '.' or $file === '..') {
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
