<?php

session_start();
if(!isset($_SESSION["username"])) {
    header("Location:user_login.php");
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/style.css">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <style>

    .heading {
      width: 70%;
      margin: 200px auto;
    }

    .heading h1 {
      text-align: center;
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

    .heading h1 span {
  font-size: 30px;
  font-weight: 600;
  color: #222;
}
  </style>
</head>
<body>
  <div class="container">
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
  <h1><span>Welcome <?php echo $_SESSION["username"];?></span></h1>
</div>
    </div>
  </div>
  <script src="../js/script.js"></script>

</body>

</html>