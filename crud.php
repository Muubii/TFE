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
        echo "Record inserted successfully";
    }
    $sdsd->close();
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
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Product Management</h1>
    </header>

    <div class="search-container">
        <a href="index.html">
            <button>Home</button>
        </a>
    </div>
    
    <div class="form-container">
        <form class="registration-form" action="crud.php" method="POST">
            <div class="form-group">
                <label for="productid">Product ID:</label>
                <input type="text" id="productid" name="productid" placeholder="Enter your product ID" required>
            </div>
            <div class="form-group">
                <label for="naam">Naam:</label>
                <input type="text" id="naam" name="naam" placeholder="Enter product name" required>
            </div>
            <div class="form-group">
                <label for="fabriek">Fabriek:</label>
                <input type="text" id="fabriek" name="fabriek" placeholder="Enter Fabriek name" required>
            </div>
            <div class="form-group">
                <label for="verkoop_prijs">verkoop prijs:</label>
                <input type="text" id="verkoop_prijs" name="verkoop_prijs" placeholder="Enter verkoop prijs" required>
            </div>
            <div class="form-group">
                <label for="inkoop_prijs">Inkoop prijs:</label>
                <input type="text" id="inkoop_prijs" name="inkoop_prijs" placeholder="Enter Inkoop prijs" required>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" placeholder="Enter type" required>
            </div>
            <button type="submit" name="send">Submit</button>
        </form>
    </div>

    <table>
        <?php
        if ($result->num_rows > 0) {
            $firstrow = $result->fetch_assoc();

            echo "<tr>";
            foreach ($firstrow as $key => $value) {
                echo "<th>$key</th>";
            }
            echo "<th>Actions</th>";
            echo "</tr>";

            $result->data_seek(0);  

            while ($row = $result->fetch_assoc()) {
                echo "<tr class='dataRow'>";
                foreach ($row as $key => $value) {
                    echo "<td>";
                    echo htmlspecialchars($value ?? '');  
                    echo "</td>";
                }
                echo "<td><a href='update.php?productid=" . htmlspecialchars($row['productid'] ?? '') . "' class='btn'>Update</a></td>";
                echo "<td><a href='delete.php?productid=" . htmlspecialchars($row['productid'] ?? '') . "' class='btn'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "No products found";
        }
        ?>
    </table>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #F4F4F4;
            color: #333;
        }

        header {
            background: #003366;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .search-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .search-container button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #003366;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #00509e;
        }

        .form-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .registration-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .registration-form button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #003366;
            color: white;
            cursor: pointer;
            align-self: flex-end;
        }

        .registration-form button:hover {
            background-color: #00509e;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 18px;
            text-align: left;
        }

        table th, table td {
            padding: 12px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #003366;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        .btn {
            background: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #00509e;
        }
    </style>
</body>
</html>
