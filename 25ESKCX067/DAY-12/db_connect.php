<?php
// db_connect.php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "12345";
$dbName = "myapp";

$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
