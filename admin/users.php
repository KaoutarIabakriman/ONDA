<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion si non connecté
  header("Location: ../admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "../connection.php";

// Récupère les informations sur les enseignants depuis la base de données
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Ferme la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
  <title>View Users</title>
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
</head>

<body>
    <?php
    require_once "admin_dashboard.html";
    ?>
   <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
      </div>
      <div class="heading">
        <h1>View Users</h1>

      </div>
  

<div class="table-responsive">
  <table class="table table-striped ">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>

    <tbody>
<?php
  $index = 1;
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr data-id='{$row['id']}'>";
    echo "<th scope='row'>{$index}</th>";
    echo "<td>{$row['username']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['role']}</td>";
    echo "<td>";
    echo "<button class='btn btn-danger btn-sm' onclick='deleteRow({$row['id']})'>Delete</button>";
    echo "</td>";
    echo "</tr>";
    $index++;
  }
?>
</tbody>


  </table>
</div>
</div>

</body>
<script src="../js/script.js"></script>
<script>

function deleteRow(id) {
  if (confirm("Are you sure you want to delete this row?")) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id=" + id);

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          alert("Row deleted successfully!");
          var row = document.querySelector("tr[data-id='" + id + "']");
          if (row) {
            row.parentNode.removeChild(row);
            renumberRows();
          }
        } else {
          alert("Error deleting row: " + response.error);
        }
      } else {
        alert("Error deleting row: " + xhr.statusText);
      }
    };
  }
}

function renumberRows() {
  var tableRows = document.querySelectorAll("tbody tr");
  tableRows.forEach(function(row, index) {
    var cells = row.children;
    cells[0].innerText = index + 1; // Update the first cell to reflect the new row number
  });
}
</script>



</html>
<?php
// Ferme la connexion à la base de données
mysqli_close($conn);
?>