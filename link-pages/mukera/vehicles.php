<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['manager', 'admin'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['add_vehicle'])) {
    $vehicle_id = trim($_POST['vehicle_id']);
    $type = $_POST['type'];
    $location = trim($_POST['location']);

    try {
        $stmt = $pdo->prepare("INSERT INTO vehicles (vehicle_id, type, location) VALUES (?, ?, ?)");
        $stmt->execute([$vehicle_id, $type, $location]);
        $success = "Vehicle added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding vehicle: " . $e->getMessage();
    }
}

if (isset($_POST['update_status'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $status = $_POST['status'];
    $location = trim($_POST['location']);

    try {
        $stmt = $pdo->prepare("UPDATE vehicles SET status = ?, location = ? WHERE id = ?");
        $stmt->execute([$status, $location, $vehicle_id]);
        $success = "Vehicle status updated!";
    } catch (PDOException $e) {
        $error = "Error updating vehicle: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM vehicles");
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Vehicle Management</h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <h3>Add New Vehicle</h3>
        <form method="POST">
            <div class="form-group">
                <label for="vehicle_id">Vehicle ID:</label>
                <input type="text" name="vehicle_id" id="vehicle_id" placeholder="e.g., V001" required>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <select name="type" id="type" required>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                    <option value="car">Car</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" placeholder="Location" required>
            </div>
            <button type="submit" name="add_vehicle">Add Vehicle</button>
        </form>

        <h3>Current Vehicles</h3>
        <table>
            <thead>
                <tr>
                    <th>Vehicle ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vehicle['vehicle_id']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['type']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['status']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['location']); ?></td>
                        <td><?php echo $vehicle['last_updated']; ?></td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                                <select name="status">
                                    <option value="available" <?php echo $vehicle['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                    <option value="allocated" <?php echo $vehicle['status'] === 'allocated' ? 'selected' : ''; ?>>Allocated</option>
                                    <option value="maintenance" <?php echo $vehicle['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                </select>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($vehicle['location']); ?>" required>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>