<?php 
$servername = "docker-mysql-1";
$dbUsername = "root";
$password = "password";
$database = "toolsforever";

$connection = new mysqli($servername, $dbUsername, $password, $database);

session_start(); 

$originalProductid = $_GET['aantal'] ?? null;

$user = null;
if ($originalProductid) {
    $stmt = $connection->prepare("SELECT * FROM vooraad WHERE aantal = ?");
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
    $newAantal =  $_POST['aantal'];
    $newbedrijf =  $_POST['bedrijf_idbedrijf'];
    $newProduct =  $_POST['product_productid'];

// Assuming connection is already established and $connection is your mysqli object
if ($stmt = $connection->prepare("UPDATE vooraad SET aantal = ?, bedrijf_idbedrijf = ?, product_productid = ?=WHERE vooraad = ?")) {
    // Bind parameters: Assuming all parameters are integers except for naam, fabriek, and type which are strings
    $stmt->bind_param("iiii", $newAantal, $newbedrijf, $newProduct, $originalProductid);

    // Execute the query
    if ($stmt->execute()) {
        header('Location: vooraad.php'); 
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
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update item</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <h2>Update item</h2>
    <form class="registration-form" action="" method="POST">
        <input type="hidden" name="original_productid" value="<?php echo htmlspecialchars($user['aantal'] ?? ''); ?>">

        <label for="aantal">aantal ID:</label>
        <input type="text" id="aantal" name="aantal" value="<?php echo htmlspecialchars($user['aantal'] ?? ''); ?>" required>
        
        <label for="naam">bedijf:</label>
        <input type="text" id="naam" name="naam" placeholder="Enter new naam" required>

        <label for="fabriek">product:</label>
        <input type="text" id="fabriek" name="fabriek" placeholder="Enter new fabriek" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>

