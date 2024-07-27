<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../connection.php";

    // Sanitize input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to prevent SQL injection
    $query = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, md5($password)); // Bind parameters (email and hashed password)
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count == 1) {
        $row = $result->fetch_assoc(); // Fetch the user data
        
        // Store username in session
        $_SESSION["username"] = $row["username"]; // Ensure this field exists in your database

        header("Location: users_login.php");
        exit();
    } else {
        echo '
        <script>
           alert("Email or password invalid");
            window.location.href = "user_login.php";
        </script>
        ';
    }

    $stmt->close();
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: #bddaf1;">
<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h3 class="mb-5" style="color: #bddaf1;">Sign in</h3>

            <form method="post">
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="typeEmailX-2" name="email" class="form-control form-control-lg" />
                    <label class="form-label" for="typeEmailX-2">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="typePasswordX-2" name="password" class="form-control form-control-lg" />
                    <label class="form-label" for="typePasswordX-2">
                        <i class="fas fa-lock"></i> Password
                    </label>
                </div>

                <a href="forgot_password.php" class="text-decoration-none">Forgot password?</a>

                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" type="submit">
                    <i class="fas fa-arrow-right"></i> Login
                </button>

                <hr class="my-4">

                <p>Don't have an account? <a href="user_register.php">Sign up</a></p>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/all.min.js"></script>
</body>
</html>