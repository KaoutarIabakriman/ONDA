<?php
include_once "../connection.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the id from the POST data and convert it to an integer cause the data via http is send on string format
    $id = intval($_POST["id"]); 
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    // Bind the ID parameter to the SQL query
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        echo json_encode(["success" => true]); //if the execution is successful, return a JSON response idicating success
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    //close the prepared statement
    $stmt->close();
}
$conn->close();

?>