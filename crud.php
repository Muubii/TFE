<?php
$servername = "docker-mysql-1";
$username = "root";
$password = "password";
$database = "toolsforever";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
        $productid = $_POST['productid'];
        $naam = $_POST['naam'];
        $fabriek = $_POST['fabriek'];
        $verkoopprijs = $_POST['verkoop_prijs'] ?? 0;
        $inkoopprijs = $_POST['inkoop_prijs'] ?? 0;
        $type = $_POST['type'];

        $sdsd = $conn->prepare("INSERT INTO product (productid, naam, fabriek, `verkoop prijs`, `inkoopprijs`, `type`) VALUES (?, ?, ?, ?, ?, ?)");
        $sdsd->bind_param("issiis", $productid, $naam, $fabriek, $verkoopprijs, $inkoopprijs, $type);

        // Execute the SQL statement
        if ($sdsd->execute()) {
            echo "w";
        $sdsd->close();
    }
}


$query = "SELECT * FROM product";
$stmt = $conn->prepare($query) or die("Error 1");
$stmt->execute() or die("Error 2");
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.html">
        <button>home</button>
    </a>
  
    <table>
        <?php
        if ($result->num_rows > 0) {
            $firstrow = $result->fetch_assoc();

            echo "<tr>";
            foreach ($firstrow as $key => $value) {
                echo "<th>$key</th>";
            }
            echo "</tr>";

            $result->data_seek(0);  // Reset the result set to the beginning

            while ($row = $result->fetch_assoc()) {
                echo "<tr class='dataRow'>";
                foreach ($row as $key => $value) {
                    echo "<td>";
                    echo htmlspecialchars($value ?? '');  // Use the null coalescing operator to ensure a string
                    echo "</td>";
                }
                // Adjust the links to point to the correct field, assuming 'productid' might be the identifier
                echo "<td><a href='update.php?productid=" . htmlspecialchars($row['productid'] ?? '') . "' class='btn'>update</a></td>";
                echo "<td><a href='delete.php?productid=" . htmlspecialchars($row['productid'] ?? '') . "' class='btn'>delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "Er zijn geen producten gevonden";
        }
        ?>

        <form class="registration-form" action="crud.php" method="POST">
            <label for="productid">productid:</label>
            <input type="text" id="productid" name="productid" placeholder="Enter your productid" required>
            <label for="naam">naam:</label>
            <input type="text" id="naam" name="naam" placeholder="Enter your naam" required>
            <label for="fabriek">fabriek:</label>
            <input type="text" id="fabriek" name="fabriek" placeholder="Enter your fabriek" required>
            <label for="verkoop_prijs">verkoop prijs:</label>
            <input type="text" id="verkoop_prijs" name="verkoop_prijs" placeholder="Enter your verkoop prijs" required>
            <label for="inkoop_prijs">inkoop prijs:</label>
            <input type="text" id="inkoop_prijs" name="inkoop_prijs" placeholder="Enter your inkoop prijs" required>
            <label for="type">type:</label>
            <input type="text" id="type" name="type" placeholder="Enter your type" required>
            <button type="submit" name="send">Submit</button>
        </form>
    </table>

    <style>
        table, tr, td {
            border: solid 1px black;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            height: 5vh;
            width: 5%;
        }
        .curd {
            text-align: center;
        }
        tr:hover {
            background-color: #aeb7c0;
        }
    </style>
</body>
</html>
