<?php
$host = "localhost";      // Database host
$user = "root";           // Database username
$pass = "";               // Database password
$dbname = "studentportal"; // Your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
