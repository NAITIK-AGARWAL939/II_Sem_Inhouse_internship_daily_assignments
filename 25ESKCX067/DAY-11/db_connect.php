<?php
$servername = "localhost";
$username = "root";
$password = "12345";
$database="industrial_training";


$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully to the database!";
?>