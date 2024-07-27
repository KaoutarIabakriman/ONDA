<?php
session_start();

include_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  
  $query = "SELECT admin_id FROM admin WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {

    $_SESSION["username"] = $username;

    header("Location: admin/welcome.php");
    exit();
  } else {
    echo '
    <script>
       alert("Username or password invalid");
      
</script>
';
}
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrator</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      height: 100vh;
      width: 100%;
      background-image: radial-gradient(circle, #6db4ed 39%, #656bb8 82%);
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    
  </style>
</head>
<body>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Administrator login</h2>
      <form action="admin_login.php" method="post">
        <input type="text" name="username" placeholder="Username" class="form-control" required>
        <input type="password" name="password" placeholder="Password" class="form-control" required>
        <button type="submit" class="btn btn-primary ">login</button>
      </form>
    </div>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script>
</body>
</html>