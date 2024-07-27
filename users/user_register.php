<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../connection.php';
    if (isset($_POST['register'])) { //determine if the variables are declared
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role'];

        // Hash both password fields
        $hashed_password = md5($password);
        $hashed_confirm_password = md5($confirm_password);

        // Check if passwords match
        if ($hashed_password !== $hashed_confirm_password) {
            echo '<script>alert("Passwords do not match!");
            window.location.href = "user_register.php";
            </script>';
        }

        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail); //execute the query
        if (!$result) {//check if the query was successful
            echo "Error: " . $conn->error;
            exit;
        }
        if ($result->num_rows > 0) {//check if the query result has a row
            echo '<script>alert("Email Address Already Exists !");
             window.location.href = "user_register.php";
             </script>';
        } else {
            $insertQuery = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
            if ($conn->query($insertQuery) === TRUE) {
                header("Location: ../home.html");
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body style="background-color: #bddaf1;">
<section class="vh-80" >
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong"  style="border-radius: 1rem">
          <div class="card-body p-5 text-center">

            <h3 class="mb-5" style="color: #bddaf1;">Sign up</h3>

            <form method="post">
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="username" name="username" class="form-control form-control-lg" />
                    <label class="form-label" for="username" required>
                        <i class="fas fa-user"></i> Username
                    </label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" />
                    <label class="form-label" for="email" required>
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-lg" />
                    <label class="form-label" for="confirm_password" required>
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control form-control-lg" />
                    <label class="form-label" for="password" required>
                        <i class="fas fa-lock"></i> Password
                    </label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="role" name="role" class="form-control form-control-lg" />
                    <label class="form-label" for="role" required>
                        <i class="fas fa-user-tag"></i> Role
                    </label>
                </div>

                <?php if (isset($error)) {?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error;?>
                    </div>
                <?php }?>

                <button name="register" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" type="submit">
                    <i class="fas fa-arrow-right"></i> Register
                </button>

                <hr class="my-4">

                <p>Already have an account? <a href="user_login.php">Login</a></p>
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