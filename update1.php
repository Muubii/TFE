<?php 
$servername = "docker-mysql-1";
$dbUsername = "root";
$password = "password";
$database = "toolsforever";


$connection = new mysqli($servername, $dbUsername, $password, $database);


if ($connection->connect_error) {
    die("Verbinding mislukt: " . $connection->connect_error);
}

session_start(); 


$originalProductid = $_GET['aantal'] ?? null;

$user = null;
if ($originalProductid) {

    $stmt = $connection->prepare("SELECT * FROM vooraad WHERE aantal = ?");
    if ($stmt) {
        $stmt->bind_param("i", $originalProductid); 
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "Fout bij het voorbereiden van de verklaring: " . $connection->error;
    }
}

$bedrijven = [];
$result = $connection->query("SELECT * FROM bedrijf");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bedrijven[] = $row;
    }
} else {
    echo "Fout bij het ophalen van bedrijven: " . $connection->error;
}


$producten = [];
$result = $connection->query("SELECT * FROM product");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $producten[] = $row;
    }
} else {
    echo "Fout bij het ophalen van producten: " . $connection->error;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $originalProductid = $_POST['original_productid'];
    $newAantal =  $_POST['aantal'];
    $newbedrijf =  $_POST['bedrijf_idbedrijf'];
    $newProduct =  $_POST['product_productid'];


    $stmt = $connection->prepare("SELECT COUNT(*) FROM vooraad WHERE aantal = ?");
    $stmt->bind_param("i", $newAantal);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0 && $newAantal != $originalProductid) {
        echo "Fout: Dubbele invoer voor sleutel 'PRIMARY'. De aantal waarde bestaat al.";
    } else {
        if ($stmt = $connection->prepare("UPDATE vooraad SET aantal = ?, bedrijf_idbedrijf = ?, product_productid = ? WHERE aantal = ?")) {
            // Bind parameters
            $stmt->bind_param("iiii", $newAantal, $newbedrijf, $newProduct, $originalProductid);
        
            // Voer de query uit
            if ($stmt->execute()) { 
                header('Location: vooraad.php'); 
                exit();
    }
        }
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

        <label for="aantal">Aantal ID:</label>
        <input type="text" id="aantal" name="aantal" value="<?php echo htmlspecialchars($user['aantal'] ?? ''); ?>" required>
        
        <label for="bedrijf">Bedrijf:</label>
        <select id="bedrijf" name="bedrijf_idbedrijf" required>
            <?php if (empty($bedrijven)): ?>
                <option value="">Geen bedrijven beschikbaar</option>
            <?php else: ?>
                <?php foreach ($bedrijven as $bedrijf): ?>
                    <option value="<?php echo $bedrijf['idbedrijf']; ?>" <?php echo (isset($user['bedrijf_idbedrijf']) && $user['bedrijf_idbedrijf'] == $bedrijf['idbedrijf']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($bedrijf['adres']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label for="product">Product:</label>
        <select id="product" name="product_productid" required>
            <?php if (empty($producten)): ?>
                <option value="">Geen producten beschikbaar</option>
            <?php else: ?>
                <?php foreach ($producten as $product): ?>
                    <option value="<?php echo $product['productid']; ?>" <?php echo (isset($user['product_productid']) && $user['product_productid'] == $product['productid']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($product['naam']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
