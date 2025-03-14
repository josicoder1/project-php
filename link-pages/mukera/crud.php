<?php
// Step 1: Connect to the database
$servername = "localhost";
$username = "root";    // Default XAMPP username
$password = "";        // Default XAMPP password
$dbname = "testdb";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Create the `users` table (run once, then comment out)
$sql_create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL
)";

if ($conn->query($sql_create_table) === TRUE) {
    echo "Table 'users' created successfully!<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Step 3: Insert Data
$sql_insert = "INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')";
if ($conn->query($sql_insert) === TRUE) {
    echo "New record inserted!<br>";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Step 4: Fetch Data
$sql_select = "SELECT * FROM users";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    echo "<h3>Users List:</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";
    }
} else {
    echo "No users found.";
}

// Close the connection
$conn->close();
?>