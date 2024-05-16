<?php 
$servername = "docker-mysql-1";
$dbUsername = "root"; 
$password = "password";
$database = "toolsforever";

// Create connection
$connection = new mysqli($servername, $dbUsername, $password, $database);

session_start(); 

if (isset($_GET['productid'])) {
    $username = $connection->real_escape_string($_GET['productid']);

    $query = "DELETE FROM product WHERE productid = ?";
    $stmt = $connection->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        header('Location: crud.php');
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
}

$connection->close();
?>

