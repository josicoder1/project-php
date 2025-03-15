<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['admin_loggedin'])) {
    header("Location: admin_login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = trim($_POST['vehicle_id']);
    $type = $_POST['type'];
    $location = trim($_POST['location']);

    try {
        // Check if vehicle ID exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM vehicles WHERE vehicle_id = ?");
        $stmt->execute([$vehicle_id]);

        if ($stmt->fetchColumn() > 0) {
            $error = "Vehicle ID already exists!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO vehicles 
                (vehicle_id, type, location, status) 
                VALUES (?, ?, ?, 'available')");
            $stmt->execute([$vehicle_id, $type, $location]);

            $success = "Vehicle added successfully!";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Vehicle</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add New Vehicle</h2>

        <?php if ($error): ?>
            <div class="notification error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="notification success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Vehicle ID</label>
                <input type="text" name="vehicle_id" required>
            </div>

            <div class="form-group">
                <label>Vehicle Type</label>
                <select name="type" required>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                    <option value="car">Car</option>
                </select>
            </div>

            <div class="form-group">
                <label>Current Location</label>
                <input type="text" name="location" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Vehicle</button>
            <a href="admin_dashboard.php?section=vehicles" class="btn">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>
