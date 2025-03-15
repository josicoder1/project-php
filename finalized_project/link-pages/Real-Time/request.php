<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $request_id = 'REQ' . str_replace('.', '', microtime(true));
    $purpose = trim($_POST['purpose']);
    $date = $_POST['request_date'];
    $time = $_POST['request_time'];
    $destination = trim($_POST['destination']);

    $document_path = null;
    $upload_dir = 'uploads/';
    if (isset($_FILES['document']) && $_FILES['document']['error'] == UPLOAD_ERR_OK) {
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $document_path = $upload_dir . time() . '_' . basename($_FILES['document']['name']);
        if (!move_uploaded_file($_FILES['document']['tmp_name'], $document_path)) {
            $error = "Failed to upload document.";
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO transport_requests (request_id, purpose, request_date, request_time, destination, submitted_by, document_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$request_id, $purpose, $date, $time, $destination, $_SESSION['user_id'], $document_path]);
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Transport Request</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>New Transport Request</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <textarea name="purpose" id="purpose" placeholder="Purpose" required></textarea>
            </div>
            <div class="form-group">
                <label for="request_date">Date:</label>
                <input type="date" name="request_date" id="request_date" required>
            </div>
            <div class="form-group">
                <label for="request_time">Time:</label>
                <input type="time" name="request_time" id="request_time" required>
            </div>
            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" name="destination" id="destination" placeholder="Destination" required>
            </div>
            <div class="form-group">
                <label for="document">Document (optional):</label>
                <input type="file" name="document" id="document">
            </div>
            <button type="submit" name="submit">Submit Request</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>