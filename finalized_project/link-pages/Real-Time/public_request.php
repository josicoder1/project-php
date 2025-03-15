<?php
require 'db_config.php';
require 'id_generator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $request_id = generateRequestID($pdo);
        $stmt = $pdo->prepare("INSERT INTO transport_requests 
            (request_id, purpose, request_date, request_time, destination, email, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')");

        $stmt->execute([
            $request_id,
            $_POST['purpose'],
            $_POST['date'],
            $_POST['time'],
            $_POST['destination'],
            $_POST['email']
        ]);

        header("Location: success.php?id=" . urlencode($request_id));
        exit();
    } catch (PDOException $e) {
        $error = $e->getCode() == 23000
            ? "Submission failed. Please try again."
            : "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Transport Request</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Submit Transport Request</h2>
        <?php if (isset($error)): ?>
            <div class="notification error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <form method="POST">
                <div class="form-group">
                    <label>Your Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Purpose of Request</label>
                    <textarea name="purpose" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Date Needed</label>
                    <input type="date" name="date" required>
                </div>
                <div class="form-group">
                    <label>Time Needed</label>
                    <input type="time" name="time" required>
                </div>
                <div class="form-group">
                    <label>Destination</label>
                    <input type="text" name="destination" required>
                </div>
                <button type="submit" class="cta-button">
                    Submit Request
                </button>
            </form>
    </div>
</body>

</html>