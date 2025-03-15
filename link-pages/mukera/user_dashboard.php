<?php
session_start();
require 'db_config.php';

// User authentication
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Get user notifications
$notifications = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE user_email = ?
    ORDER BY created_at DESC
");
$notifications->execute([$_SESSION['user_email']]);

// Mark as read
$pdo->prepare("UPDATE notifications SET status = 'read' WHERE user_email = ?")
    ->execute([$_SESSION['user_email']]);
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
    <style>
        .notification {
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
        }

        .unread {
            border-color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_email']) ?></h2>

    <div class="notifications">
        <h3>Your Notifications</h3>
        <?php while ($note = $notifications->fetch()): ?>
            <div class="notification <?= $note['status'] === 'unread' ? 'unread' : '' ?>">
                <p><?= htmlspecialchars($note['message']) ?></p>
                <small><?= $note['created_at'] ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>