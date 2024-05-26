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
    $aantal = $_POST['aantal'];
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];

    // Insert new address if it doesn't exist
    $stmt = $conn->prepare("INSERT INTO bedrijf (adres) VALUES (?) ON DUPLICATE KEY UPDATE adres=adres");
    $stmt->bind_param("s", $adres);
    $stmt->execute();
    $stmt->close();
    
    // Get the id of the address
    $stmt = $conn->prepare("SELECT idbedrijf FROM bedrijf WHERE adres = ?");
    $stmt->bind_param("s", $adres);
    $stmt->execute();
    $stmt->bind_result($bedrijf_idbedrijf);
    $stmt->fetch();
    $stmt->close();
    
    // Insert new product if it doesn't exist
    $stmt = $conn->prepare("INSERT INTO product (naam) VALUES (?) ON DUPLICATE KEY UPDATE naam=naam");
    $stmt->bind_param("s", $naam);
    $stmt->execute();
    $stmt->close();
    
    // Get the id of the product
    $stmt = $conn->prepare("SELECT productid FROM product WHERE naam = ?");
    $stmt->bind_param("s", $naam);
    $stmt->execute();
    $stmt->bind_result($product_productid);
    $stmt->fetch();
    $stmt->close();


    $stmt = $conn->prepare("INSERT INTO vooraad (bedrijf_idbedrijf, aantal, product_productid) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $bedrijf_idbedrijf, $aantal, $product_productid);

    // Execute the SQL statement
    if ($stmt->execute()) {
        echo "Created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement  
    $stmt->close();
}


$query = "SELECT vooraad.aantal, product.naam, bedrijf.adres
          FROM vooraad
          INNER JOIN product ON vooraad.product_productid = product.productid
          INNER JOIN bedrijf ON vooraad.bedrijf_idbedrijf = bedrijf.idbedrijf";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <a href="index.html"><button>home</button></a>

  <form class="registration-form" action="vooraad.php" method="POST">
      <label for="aantal">Aantal:</label>
      <input type="text" id="aantal" name="aantal" placeholder="Enter your aantal" required>
      <label for="naam">Naam:</label>
      <input type="text" id="naam" name="naam" placeholder="Enter product naam" required>
      <label for="adres">Adres:</label>
      <input type="text" id="adres" name="adres" placeholder="Enter adres" required>
      <button type="submit" name="send">Submit</button>
  </form>
  <table>
  <?php
  if ($result->num_rows > 0) {
      echo "<tr>";
      echo "<th>Aantal</th>";
      echo "<th>Naam</th>";
      echo "<th>Adres</th>";
      echo "<th>Update</th>";
      echo "<th>Delete</th>";
      echo "</tr>";

      while ($row = $result->fetch_assoc()) {
          $aantalColor = ($row['aantal'] < 10) ? 'red' : 'black'; // Als aantal onder 10 is, maak het rood
          echo "<tr>";
          echo "<td style='color:{$aantalColor};'>" . htmlspecialchars($row['aantal']) . "</td>";
          echo "<td>" . htmlspecialchars($row['naam']) . "</td>";
          echo "<td>" . htmlspecialchars($row['adres']) . "</td>";
          echo "<td><a href='update1.php?aantal=" . htmlspecialchars($row['aantal']) . "'>update</a></td>";
          echo "<td><a href='delete1.php?aantal=" . htmlspecialchars($row['aantal']) . "'>delete</a></td>";
          echo "</tr>";
      }
    }
  $stmt->close();
  $conn->close();
  ?>
</table>

  <link rel="stylesheet" href="style.css">
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

        .search-container, .registration-form {
            display: flex;
            justify-content: center;
            padding: 20px;
            flex-wrap: wrap;
        }

        .search-container input[type="text"], .registration-form input[type="text"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .search-container button, .registration-form button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #003366;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover, .registration-form button:hover {
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

        a button {
            background: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px;
            text-align: center;
        }

        a button:hover {
            background-color: #00509e;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }
  </style>
</body>
</html>
