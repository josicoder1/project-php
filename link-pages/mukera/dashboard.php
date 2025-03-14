<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Transport System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Transport Management System</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo $role; ?>)</p>
        <nav>
            <a href="request.php">New Request</a>
            <?php if ($role === 'manager' || $role === 'admin'): ?>
                <a href="approvals.php">Pending Approvals</a>
                <a href="vehicles.php">Vehicle Status</a>
            <?php endif; ?>
            <?php if ($role === 'admin'): ?>
                <a href="register.php">Register User</a>
            <?php endif; ?>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</body>

</html>