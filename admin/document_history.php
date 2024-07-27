<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document History</title>
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
require_once "../admin/admin_dashboard.html";
?>
<div class="main">
    <div class="topbar">
        <div class="toggle">
            <ion-icon name="menu-outline"></ion-icon>
        </div>
    </div>
    <div class="heading">
        <h1>History</h1>
    </div>
    <div class="container mt-5">
        <table class="table table-striped table-bordered">
            
            <tbody>
            <?php
include_once "../connection.php";

$query = "SELECT * FROM document_history ORDER BY date_time DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date and Time</th>
                    <th>Author</th>
                    <th>Changes Made</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['date_time']}</td>
                <td>{$row['author']}</td>
                <td>{$row['changes']}</td>
              </tr>";
    }
    echo '</tbody></table>';
} else {
    echo "<p>No history available</p>";
}

$conn->close();
?>
            </tbody>
        </table>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/all.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
