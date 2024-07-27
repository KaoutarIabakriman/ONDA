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
      font-size:40px;
      font-weight:700;
      color:#222;
      letter-spacing:1px;
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
      background-color:#f8f8f8;
    }

</style>
<body>
<?php
    require_once "user_dashboard.html";
    ?>
   <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
      </div>
      <div class="heading">
        <h1>Archives</h1>
      </div>
      <div class="form-group">
            <input type="text" id="search_input" class="form-control" placeholder="Search by first letter">
        </div>
       <div id="folder_table" class="table-responsive"></div>




    <script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/all.min.js"></script>
<script src="../js/script.js"></script>
<script>

  $(document).ready(function() {
    load_folder_list();
    
    function load_folder_list() {
      var action = "fetch";
      $.ajax({
        url : "user_action.php",
        method: "POST",
        data: {action: action},
        success: function(data) {
          $('#folder_table').html(data);
        }
      });
    }

    $(document).on('click', '.view_files', function() {
      var folder_name = $(this).data("name");
      window.location.href = 'user_file.php?folder_name=' + folder_name;
    });
    $('#search_input').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#folder_table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
  });
</script>




</body>
</html>