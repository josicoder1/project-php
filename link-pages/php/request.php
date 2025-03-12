<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    // Generate unique request_id using microtime
    $request_id = 'REQ' . str_replace('.', '', microtime(true)); // e.g., REQ1741545123456
    $purpose = $_POST['purpose'];
    $date = $_POST['request_date'];
    $time = $_POST['request_time'];
    $destination = $_POST['destination'];

    // Handle file upload
    $document_path = null;
    $upload_dir = 'uploads/';

    if (isset($_FILES['document']) && $_FILES['document']['error'] == UPLOAD_ERR_OK) {
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("Failed to create upload directory");
            }
        }

        $document_path = $upload_dir . time() . '_' . basename($_FILES['document']['name']);
        if (!move_uploaded_file($_FILES['document']['tmp_name'], $document_path)) {
            die("Failed to move uploaded file. Check directory permissions.");
        }
    } elseif (isset($_FILES['document']) && $_FILES['document']['error'] != UPLOAD_ERR_NO_FILE) {
        die("Upload error: " . $_FILES['document']['error']);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO transport_requests (request_id, purpose, request_date, request_time, destination, submitted_by, document_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$request_id, $purpose, $date, $time, $destination, $_SESSION['user_id'], $document_path]);
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>New Transport Request</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>New Transport Request</h2>
        <form method="POST" enctype="multipart/form-data">
            <textarea name="purpose" placeholder="Purpose" required></textarea>
            <input type="date" name="request_date" required>
            <input type="time" name="request_time" required>
            <input type="text" name="destination" placeholder="Destination" required>
            <input type="file" name="document">
            <button type="submit" name="submit">Submit Request</button>
        </form>
    </div>
</body>

</html>