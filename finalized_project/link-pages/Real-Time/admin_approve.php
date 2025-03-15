<?php
session_start();
require 'db_config.php';

// Admin authentication
if (!isset($_SESSION['admin_loggedin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $comments = htmlspecialchars($_POST['comments'] ?? '');

    try {
        // 1. Update request status
        $stmt = $pdo->prepare("UPDATE transport_requests SET status = ? WHERE id = ?");
        $stmt->execute([$action, $request_id]);

        // 2. Get request details
        $stmt = $pdo->prepare("SELECT email, request_id FROM transport_requests WHERE id = ?");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch();

        // 3. Create notification
        if ($request) {
            $message = "Your request {$request['request_id']} has been $action.";
            if (!empty($comments)) $message .= " Comments: $comments";

            $stmt = $pdo->prepare("INSERT INTO notifications (user_email, message) VALUES (?, ?)");
            $stmt->execute([$request['email'], $message]);
        }

        header("Location: admin_dashboard.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>