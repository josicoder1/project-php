<?php
// Database configuration
$host = 'localhost';
$dbname = 'store_database_system';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $purpose = $_POST['purpose'];
    $request_date = $_POST['request_date'];
    $request_time = $_POST['request_time'];
    $destination = $_POST['destination'];

    // Generate a unique request ID
    $request_id = 'REQ-' . uniqid();

    // Insert the request into the database
    $stmt = $pdo->prepare("
        INSERT INTO transport_requests (request_id, purpose, request_date, request_time, destination, email)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    // Use a default email for anonymous requests
    $email = 'anonymous@example.com';

    // Execute the query
    if ($stmt->execute([$request_id, $purpose, $request_date, $request_time, $destination, $email])) {
        $success_message = "Request submitted successfully!";
    } else {
        $error_message = "Failed to submit the request. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Transport Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Submit Transport Request</h2>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="purpose">Purpose:</label>
            <input type="text" id="purpose" name="purpose" required>

            <label for="request_date">Request Date:</label>
            <input type="date" id="request_date" name="request_date" required>

            <label for="request_time">Request Time:</label>
            <input type="time" id="request_time" name="request_time" required>

            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>

            <button type="submit">Submit Request</button>
        </form>
    </div>
</body>

</html>