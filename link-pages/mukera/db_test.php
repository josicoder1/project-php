<?php
$servername = "localhost";
$username = "root"; // XAMPP default
$password = "";     // XAMPP default

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to MySQL successfully!";
?>