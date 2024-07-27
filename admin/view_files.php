<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .heading {
            width: 70%;
            margin: 10px auto;
        }

        .heading h1 {
            text-align: top;
            font-size: 40px;
            font-weight: 700;
            color: #222;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: grid;
            grid-template-columns: 1fr max-content 1fr;
            grid-template-rows: 27px 0;
            grid-gap: 20px;
            align-items: center;
        }

        .heading h1:after,
        .heading h1:before {
            content: " ";
            display: block;
            border-bottom: 3px solid #bddaf1;
            border-top: 3px solid #bddaf1;
            height: 20px;
            background-color: #f8f8f8;
        }
    </style>
</head>
<body>
    <?php require_once "admin_dashboard.html";
    include_once "../connection.php"; ?>
    
    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            <button type="button" name="back" id="back" class="btn" style="background-color: #bddaf1; color: #333;">
                <i class="fa-solid fa-circle-left"></i>
            </button>
        </div>
        <div class="heading">
            <h1>Files</h1>
        </div>
        <div class="form-group">
            <input type="text" id="search_input" class="form-control" placeholder="Search by first letter">
        </div>
        <?php
$base_path = 'C:/Users/kaoutar iabakriman/Documents/ONDA';
$folder_name = basename($_GET['folder_name']);
$full_path = $base_path . '/' . $folder_name;


if (is_dir($full_path)) {
    $files = array_diff(scandir($full_path), array('.', '..'));
    $output = '<table id="files_table" class="table table-bordered table-striped">
                <tr>
                    <th>File Name</th>
                    <th>Last Updated</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Download</th>
                    <th>Delete</th>
                </tr>';

    foreach ($files as $file) {
        $file_path = $full_path . '/' . $file;
        if (is_file($file_path)) {
            $file_type = mime_content_type($file_path);
            $modified_date = date('Y-m-d H:i:s', filemtime($file_path));
            
            // Fetch reference from the database
            $file_name = basename($file);
            $ref_query = "SELECT reference FROM file_references WHERE file = '$file_name' AND folder = '$folder_name'";
            $ref_result = $conn->query($ref_query);
            $reference = '';
            if ($ref_result->num_rows > 0) {
                $ref_row = $ref_result->fetch_assoc();
                $reference = $ref_row['reference'];
            }
            
            $output .= '<tr data-file="' . urlencode($file_path) . '">';
            $output .= '<td>' . htmlspecialchars($file) . '</td>';
            $output .= '<td>' . $modified_date . '</td>';
            $output .= '<td>' . $file_type . '</td>';
            $output .= '<td>';
            if ($reference == '') {
                $output .= '<button type="button" class="btn btn-info btn-sm ref-btn" data-bs-toggle="modal" data-bs-target="#refModal" data-file="' . urlencode($file_path) . '" style="background-color: #bddaf1; color: #333;"><i class="fa-regular fa-pen-to-square"></i></button>';
                $output .= '<span class="ref-text" style="display:none;"></span>';
            } else {
                $output .= '<span class="ref-text">' . htmlspecialchars($reference) . '</span>';
            }
            $output .= '</td>';
            $output .= '<td><a href="download.php?file=' . urlencode($file_path) . '" download>Download</a></td>';
            $output .= '<td><a href="#" class="delete-file" data-file="' . urlencode($file_path) . '">Delete</a></td>';
            $output .= '</tr>';
        }
    }

    $output .= '</table>';
    echo $output;
} else {
    echo 'Folder does not exist or cannot be accessed';
}
$conn->close();
?>

<div class="modal fade" id="refModal" tabindex="-1" aria-labelledby="refModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refModalLabel">Enter Reference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="ref-input" placeholder="Enter reference">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-ref">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
        
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/all.min.js"></script>
<script src="../js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileRef;//store the file reference
    var refRow;//store table row element

    $('.ref-btn').on('click', function(e) {
        e.preventDefault();
        fileRef = $(this).data('file');
        refRow = $(this).closest('tr');
        $('#refModal').modal('show');
    });

    $('#save-ref').on('click', function() {
        var refText = $('#ref-input').val();
        if (refText !== '') {
            $(refRow).find('.ref-btn').hide();
            $(refRow).find('.ref-text').text(refText).show();
            saveReference(fileRef, refText);
            $('#refModal').modal('hide');
        }
    });

    function saveReference(file, reference) {
        var folder = '<?php echo $folder_name; ?>';
        $.ajax({
            url: 'save_reference.php',
            method: 'POST',
            data: { folder: folder, file: file, reference: reference },
            success: function(response) {
                if (response === 'success') {
                    // Update reference in the table
                    $(refRow).find('.ref-text').text(reference).show();
                } else {
                    alert('Failed to save reference to the database.');
                }
            }
        });
    }

    var fileToDelete;
    var rowToDelete;

    $('.delete-file').on('click', function(e) {
        e.preventDefault();
        fileToDelete = $(this).data('file');
        rowToDelete = $(this).closest('tr');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: 'delete_file.php',
            method: 'POST',
            data: { file: fileToDelete },
            success: function(response) {
                if (response === 'success') {
                    $(rowToDelete).remove();
                    $('#deleteModal').modal('hide');
                } else {
                    alert('Failed to delete file.');
                }
            }
        });
    });
    document.getElementById('back').addEventListener('click', function() {
               // Redirect to archives.php
               window.location.href = 'archives.php';
           });
           $('#search_input').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    console.log(value); // Check if input is being captured
    $("#files_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });



            });



});
</script>
</body>
</html>
