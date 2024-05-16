<?php
$servername = "docker-mysql-1";
$username = "root";
$password = "password";
$database = "toolsforever"; // Specify your database name here


// Create connection
$conn = new mysqli($servername, $username, $password, $database); // Add $database here


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {

$productid =  $_POST['productid'];
$naam =  $_POST['naam'];
$fabriek =  $_POST['fabriek'];
$verkoopprijs =  $_POST['verkoop prijs'];
$inkoopprijs =  $_POST['inkoop prijs'];
$type =  $_POST['type'];


  $sdsd = $conn->prepare("INSERT INTO product (productid, naam, fabriek, verkoop prijs, inkoop prijs, type) VALUES (?, ?, ?, ?, ?, ?)");
  $sdsd->bind_param("issiis", $productid, $naam, $fabriek, $verkoopprijs, $inkoopprijs, $type);


    // Execute the SQL statement
    if ($sdsd->execute()) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sdsd->error;
  }
}

$query = "SELECT * FROM product";
$stmt = $conn->prepare($query) or die("Error 1");
$stmt->execute() or die("Error 2");
$row = $stmt->fetch() or die("Error 3");
$stmt->execute() or die("Error 2:");

// Fetch the first row
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
  <button>home</button></a>


    <div>
    <form method="POST">
        <input type="text">
        <button>search</button>
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
      echo "<td><a href='delete.php?productid=" . htmlspecialchars($row['productid'] ?? '') . "' class='btn '>delete</a></td>";
      echo "</tr>";
  }
} else {
  echo "Er zijn geen producten gevonden";
}

?>
</table>

    <style>
      table, tr, td{
        border: solid 1px black;
        border-collapse: collapse;
      }
      td{
        padding: 10px;
        height: 5vh;
        width: 5%;
      }

      .curd{

        text-align: center;
      }


      tr:hover{
        background-color: #aeb7c0;
      }

    </style>
</body>
</html>