<?php
session_start();
require_once 'db_config.php';

// Redirect unauthorized users
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['manager', 'admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Initialize variables
$success = $error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Add new vehicle
        if (isset($_POST['add_vehicle'])) {
            $vehicle_id = trim($_POST['vehicle_id']);
            $type = $_POST['type'];
            $location = trim($_POST['location']);

            // Validate inputs
            if (empty($vehicle_id) || empty($type) || empty($location)) {
                throw new Exception("All fields are required.");
            }

            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO vehicles (vehicle_id, type, location) VALUES (?, ?, ?)");
            $stmt->execute([$vehicle_id, $type, $location]);
            $success = "Vehicle added successfully!";
        }

        // Update vehicle status
        if (isset($_POST['update_status'])) {
            $vehicle_id = $_POST['vehicle_id'];
            $status = $_POST['status'];
            $location = trim($_POST['location']);

            // Validate inputs
            if (empty($vehicle_id) || empty($status) || empty($location)) {
                throw new Exception("All fields are required.");
            }

            // Update database
            $stmt = $pdo->prepare("UPDATE vehicles SET status = ?, location = ? WHERE id = ?");
            $stmt->execute([$status, $location, $vehicle_id]);
            $success = "Vehicle status updated!";
        }
    } catch (PDOException $e) {
        // Database error
        $error = "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        // Validation error
        $error = $e->getMessage();
    }
}

// Fetch all vehicles
try {
    $stmt = $pdo->query("SELECT * FROM vehicles");
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching vehicles: " . $e->getMessage();
    $vehicles = []; // Ensure $vehicles is defined even if there's an error
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2, h3 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .inline-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .inline-form select,
        .inline-form input {
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Vehicle Management</h2>

        <!-- Display success or error messages -->
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add New Vehicle Form -->
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

        <!-- Current Vehicles Table -->
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
                <?php if (!empty($vehicles)): ?>
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
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No vehicles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>