<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");

// Restrict to manager/admin
if (!in_array($_SESSION['role'], ['manager', 'admin'])) {
    header("Location: dashboard.php");
    exit();
}

// Add new vehicle
if (isset($_POST['add_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $type = $_POST['type'];
    $location = $_POST['location'];

    try {
        $stmt = $pdo->prepare("INSERT INTO vehicles (vehicle_id, type, location) VALUES (?, ?, ?)");
        $stmt->execute([$vehicle_id, $type, $location]);
        $success = "Vehicle added successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Update vehicle status
if (isset($_POST['update_status'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    try {
        $stmt = $pdo->prepare("UPDATE vehicles SET status = ?, location = ? WHERE id = ?");
        $stmt->execute([$status, $location, $vehicle_id]);
        $success = "Vehicle status updated!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM vehicles");
$vehicles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Vehicle Management</h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Add Vehicle Form -->
        <h3>Add New Vehicle</h3>
        <form method="POST">
            <input type="text" name="vehicle_id" placeholder="Vehicle ID (e.g., V001)" required>
            <select name="type" required>
                <option value="van">Van</option>
                <option value="truck">Truck</option>
                <option value="car">Car</option>
            </select>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit" name="add_vehicle">Add Vehicle</button>
        </form>

        <!-- Vehicle List -->
        <h3>Current Vehicles</h3>
        <table>
            <tr>
                <th>Vehicle ID</th>
                <th>Type</th>
                <th>Status</th>
                <th>Location</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?php echo $vehicle['vehicle_id']; ?></td>
                    <td><?php echo $vehicle['type']; ?></td>
                    <td><?php echo $vehicle['status']; ?></td>
                    <td><?php echo $vehicle['location']; ?></td>
                    <td><?php echo $vehicle['last_updated']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                            <select name="status">
                                <option value="available" <?php echo $vehicle['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                <option value="allocated" <?php echo $vehicle['status'] === 'allocated' ? 'selected' : ''; ?>>Allocated</option>
                                <option value="maintenance" <?php echo $vehicle['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            </select>
                            <input type="text" name="location" value="<?php echo $vehicle['location']; ?>" required>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>