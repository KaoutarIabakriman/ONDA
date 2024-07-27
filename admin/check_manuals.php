<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
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
<body>
<?php require_once "admin_dashboard.html"; ?>
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
            <h1>Manuals</h1>
        </div>
        <div class="form-group">
            <input type="text" id="search_input" class="form-control" placeholder="Search by first letter">
        </div>
    <div id="folder_table" class="table-responsive"></div>
<!-- Upload Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="change_title">Upload File</span></h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="upload_form" enctype='multipart/form-data'>
                        <p>Select File
                            <input type="file" name="upload_file">
                        </p>

                        <input type="hidden" name="hidden_folder_name" id="hidden_folder_name">
                        <input type="submit" name="upload_button" class="btn btn-info" value="Upload">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/all.min.js"></script>
    <script src="../js/script.js"></script>
    <script>
        $(document).ready(function() {
            load_folder_list();

            function load_folder_list() {
                var action = "fetch";
                $.ajax({
                    url: "manuals_action.php",
                    method: "POST",
                    data: {action: action},
                    success: function(data) {
                        $('#folder_table').html(data);
                    }
                });
            }





            $(document).on('click', '.delete', function() {
                var folder_name = $(this).data("name");
                var action = "delete";
                if (confirm("Are you sure you want to remove it?")) {
                    $.ajax({
                        url: "manuals_action.php",
                        method: "POST",
                        data: {folder_name: folder_name, action: action},
                        success: function(data) {
                            load_folder_list();
                            alert(data);
                        }
                    });
                }
            });


            $('#search_input').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#folder_table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $(document).on('click', '.upload', function() {
                var folder_name = $(this).data("name");
                $('#hidden_folder_name').val(folder_name);
                $('#uploadModal').modal('show');
            });
            $('#upload_form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('file_reference', $('#file_reference').val());
                $.ajax({
                    url: "upload_manual.php",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $('#uploadModal').modal('hide');
                        load_folder_list();
                        alert(response);
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            });
            

            $(document).on('click', '[data-dismiss="modal"]', function(e) {
                e.preventDefault();
                $(this).closest('.modal').modal('hide');
            });
            document.getElementById('back').addEventListener('click', function() {
               // Redirect to archives.php
               window.location.href = 'archives.php';
           });

        });
    </script>
</div>
</body>
</html>
