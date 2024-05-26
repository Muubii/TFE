<?php 
$servername = "docker-mysql-1";
$dbUsername = "root";
$password = "password";
$database = "toolsforever";

$connection = new mysqli($servername, $dbUsername, $password, $database);

session_start(); 

$originalProductid = $_GET['productid'] ?? null;

$user = null;
if ($originalProductid) {
    $stmt = $connection->prepare("SELECT * FROM product WHERE productid = ?");
    if ($stmt) {
        $stmt->bind_param("s", $originalProductid);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $connection->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $originalProductid = $_POST['original_productid'];
    $newProductid =  $_POST['productid'];
    $newNaam =  $_POST['naam'];
    $newFabriek =  $_POST['fabriek'];
    $newVerkoopprijs = $_POST['verkoopprijs'] ?? 0; 
    $newInkoopprijs = $_POST['inkoopprijs'] ?? 0;
    $newType =  $_POST['type'];


if ($stmt = $connection->prepare("UPDATE product SET productid = ?, naam = ?, fabriek = ?, `verkoop prijs` = ?, inkoopprijs = ?, type = ? WHERE productid = ?")) {

    $stmt->bind_param("issiisi", $newProductid, $newNaam, $newFabriek, $newVerkoopprijs, $newInkoopprijs, $newType, $originalProductid);

    // Execute the query
    if ($stmt->execute()) {
        header('Location: crud.php'); 
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
}
    
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update item</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <h2>Update item</h2>
    <form class="registration-form" action="" method="POST">
        <input type="hidden" name="original_productid" value="<?php echo htmlspecialchars($user['productid'] ?? ''); ?>">

        <label for="productid">Product ID:</label>
        <input type="text" id="productid" name="productid" value="<?php echo htmlspecialchars($user['productid'] ?? ''); ?>" required>
        
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" placeholder="Enter new naam" required>

        <label for="fabriek">Fabriek:</label>
        <input type="text" id="fabriek" name="fabriek" placeholder="Enter new fabriek" required>

        <label for="verkoopprijs">Verkoopprijs:</label>
        <input type="number" id="verkoopprijs" name="verkoopprijs" placeholder="Enter new verkoop prijs" required>
        
        <label for="inkoopprijs">Inkoopprijs:</label>
        <input type="number" id="inkoopprijs" name="inkoopprijs" placeholder="Enter new inkoop prijs" required>

        <label for="type">Type:</label>
        <input type="text" id="type" name="type" placeholder="Enter new type" required>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>

