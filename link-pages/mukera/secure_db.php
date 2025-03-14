<?php
$conn = new mysqli("localhost", "root", "", "testdb");

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);

// Set parameters
$name = "Jane Doe";
$email = "jane@example.com";
$stmt->execute();

echo "New secure record created";
$stmt->close();
?>