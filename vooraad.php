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
    $bedrijf_idbedrijf = $_POST['bedrijf_idbedrijf'];
    $aantal = $_POST['aantal'];
    $product_productid = $_POST['product_productid'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO vooraad (bedrijf_idbedrijf, aantal, product_productid) VALUES (?, ?, ?)");
    // Bind parameters to the SQL statement
    $stmt->bind_param("iii", $bedrijf_idbedrijf, $aantal, $product_productid);

    // Execute the SQL statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement  
    $stmt->close();
}


// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
//     $bedrijf_idbedrijf = $_POST['vooraad.bedrijf_idbedrijf'];
//     $aantal = $_POST['aantal'];
//     $product_productid = $_POST['vooraad.product_productid'];

//     // Prepare SQL statement
//     $stmt = $conn->prepare("INSERT INTO vooraad (vooraad.bedrijf_idbedrijf, aantal, vooraad.product_productid) VALUES (?, ?, ?)");
//     // Bind parameters to the SQL statement
//     $stmt->bind_param("sis", $bedrijf_idbedrijf, $aantal, $product_productid);

//     // Execute the SQL statement
//     if ($stmt->execute()) {
//         echo "New record created successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     // Close statement  
//     $stmt->close();
// }


// Fetch data   
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
      <label for="product_productid">Naam:</label>
      <input type="text" id="product_productid" name="product_productid" placeholder="Enter your naam" required>
      <label for="bedrijf_idbedrijf">Adres:</label>
      <input type="text" id="bedrijf_idbedrijf" name="bedrijf_idbedrijf" placeholder="Enter your adres" required>
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
