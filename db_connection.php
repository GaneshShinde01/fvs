<?php
$servername = "localhost";
$username = "root"; // your database username
$pass = "tiger"; // your database password
$dbname = "fvs"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
