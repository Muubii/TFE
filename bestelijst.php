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

            $result->data_seek(0);  

            while ($row = $result->fetch_assoc()) {
                echo "<tr class='dataRow'>";
                foreach ($row as $key => $value) {
                    echo "<td>";
                    echo htmlspecialchars($value ?? '');  
                    echo "</td>";
                }
            }
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

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 30%;
            margin: 20px;
        }

        .card h2 {
            color: #003366;
        }

        a {
            text-decoration: none;
            color: black;
            gap: 20%;
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

        .search-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .search-container input[type="text"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
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
    </style>
</body>
</html>
