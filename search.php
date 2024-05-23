<?php
$servername = "docker-mysql-1";
$username = "root";
$password = "password";
$database = "toolsforever"; // Specify your database name here

$conn = new mysqli($servername, $username, $password, $database); // Add $database here

$searchQuery = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchQuery = $_POST['search_query'];
    $query = "SELECT * FROM product WHERE naam LIKE ? OR fabriek LIKE ?";
    $stmt = $conn->prepare($query) or die("Error preparing statement");
    $likeSearchQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $likeSearchQuery, $likeSearchQuery);
} else {
    $query = "SELECT * FROM product";
    $stmt = $conn->prepare($query) or die("Error preparing statement");
}

$stmt->execute() or die("Error executing statement");
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

<div>
    <form method="POST">
        <input type="text" name="search_query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search...">
        <button type="submit" name="search">Search</button>
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
            echo "</tr>";
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
