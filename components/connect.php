<?php

$servername = 'localhost'; // Usually 'localhost' on shared hosting
$username = 'root'; // Database user
$password = ''; // Your database password
$dbname = 'elections_db'; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>