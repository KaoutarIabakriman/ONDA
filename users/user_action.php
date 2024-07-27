<?php
include_once "../connection.php";


if(isset($_POST["action"])) {
    $base_path = 'C:/Users/kaoutar iabakriman/Documents/ONDA'; // Set your base path here

    if($_POST["action"] == "fetch") {
        function id_dir($path) { // Function to check if path is a directory
            return is_dir($path);
        }

        $folder = array_filter(glob($base_path . '/*'), 'id_dir');
        $output = '
        <table class="table table-bordered table-striped">
        <tr>
            <th>Folder Name</th>
            <th>Total File</th>
            <th>Last Updated</th>
            <th>View Uploaded File</th>
        </tr>
        ';
        if(count($folder) > 0) {
            foreach($folder as $name) {
                $last_updated = date("F d, Y H:i:s", filemtime($name));
                $output .= '
                <tr>
                    <td>' . basename($name) . '</td>
                    <td>' . (count(scandir($name)) - 2) . '</td>
                    <td>' . $last_updated . '</td>
                    <td><button type="button" name="view_files" data-name="' . basename($name) . '" class="view_files btn btn-xs">
        <i class="fa-solid fa-eye" style="color: #17a2b8;"></i>
    </button></button></td>
                </tr>
                ';
            }
        } else {
            $output .= '
            <tr>
            <td colspan="7">No Folder Found</td> 
            </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }
}

$conn->close();
?>
