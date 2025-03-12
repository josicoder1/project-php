<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Transport Management System</h1>
        <nav>
            <a href="request.php">New Request</a>
            <?php if ($role === 'manager' || $role === 'admin'): ?>
                <a href="approvals.php">Pending Approvals</a>
            <?php endif; ?>
            <a href="vehicles.php">Vehicle Status</a>
            <?php if ($role === 'admin'): ?>
                <a href="register.php">Register User</a>
            <?php endif; ?>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</body>

</html>