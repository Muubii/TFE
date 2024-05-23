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


$query = "SELECT * FROM bestellingen";
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
            }
        }
        ?>
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
