<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
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
    <?php
    require_once "user_dashboard.html";
    ?>

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
include '../connection.php'; // Ensure this file correctly connects to your database

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
                </tr>';

    foreach ($files as $file) {
        $file_path = $full_path . '/' . $file;
        if (is_file($file_path)) {
            $file_type = mime_content_type($file_path);
            $modified_date = date('Y-m-d H:i:s', filemtime($file_path));
            $file_name = htmlspecialchars($file);

            // Fetch reference from the database using file name
            $stmt = $conn->prepare("SELECT reference FROM file_references WHERE file = ?");
            $stmt->bind_param("s", $file_name);
            $stmt->execute();
            $stmt->bind_result($reference);
            $stmt->fetch();
            $stmt->close();

            $output .= '<tr data-file="' . urlencode($file_name) . '">';
            $output .= '<td>' . $file_name . '</td>';
            $output .= '<td>' . $modified_date . '</td>';
            $output .= '<td>' . $file_type . '</td>';
            $output .= '<td>' . (!empty($reference) ? htmlspecialchars($reference) : 'No reference') . '</td>';
            $output .= '<td><a href="download.php?file=' . urlencode($file_path) . '" download onclick="logDownload(\'' . urlencode($file_path) . '\')">Download</a></td>';
            $output .= '</tr>';
        }
    }

    $output .= '</table>';
    echo $output;
} else {
    echo 'Folder does not exist or cannot be accessed';
}
?>




        <script src="../js/bootstrap.bundle.min.js"></script>
        <script src="../js/all.min.js"></script>
        <script src="../js/script.js"></script>
        <script>
           document.getElementById('back').addEventListener('click', function() {
               // Redirect to archives.php
               window.location.href = 'user_archive.php';
           });

           $('#search_input').on('input', function() {
               var searchTerm = $(this).val().toLowerCase();
               $('#files_table tr').each(function() {
                   var fileName = $(this).find('td:first').text().toLowerCase();
                   if (fileName.startsWith(searchTerm)) {
                       $(this).show();
                   } else {
                       $(this).hide();
                   }
               });
               $(document).ready(function() {
    var fileName;

    $('.ref-btn').on('click', function(e) {
        e.preventDefault();
        fileName = $(this).closest('tr').data('file');
        $('#refModal').modal('show');
    });

    $('#save-ref').on('click', function() {
        var refText = $('#ref-input').val();
        if (refText !== '') {
            $.ajax({
                url: '../admin/save_reference.php',
                method: 'POST',
                data: {
                    file_name: fileName,
                    reference: refText
                },
                success: function(response) {
                    if (response === 'success') {
                        var row = $('tr[data-file="' + fileName + '"]');
                        row.find('.ref-btn').hide();
                        row.find('.ref-text').text(refText).show();
                        $('#refModal').modal('hide');
                    } else {
                        alert('Failed to save reference.');
                    }
                }
            });
        }
    });
});

           });
        </script>
    </div>
</body>
</html>
