<?php
$servername = "docker-mysql-1";
$username = "root";
$password = "password";
$database = "toolsforever"; // Specify your database name here


// Create connection
$conn = new mysqli($servername, $username, $password, $database); // Add $database here

?>