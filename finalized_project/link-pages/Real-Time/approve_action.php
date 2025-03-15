<?php
include 'config.php';
if ($_SESSION['role'] !== 'manager') header("Location: dashboard.php");

$request_id = $_GET['id'];
$action = $_GET['action'];

// Update request status
$stmt = $pdo->prepare("UPDATE transport_requests SET status = ? WHERE id = ?");
$stmt->execute([$action, $request_id]);

// Record approval action
$stmt = $pdo->prepare("INSERT INTO approvals (request_id, manager_id, action) VALUES (?, ?, ?)");
$stmt->execute([$request_id, $_SESSION['user_id'], $action]);

// Escalate if pending > 48hrs (example logic)
$stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(HOUR, created_at, NOW()) AS hours_pending FROM transport_requests WHERE id = ?");
$stmt->execute([$request_id]);
$time = $stmt->fetch();

if ($time['hours_pending'] > 48) {
    $stmt = $pdo->prepare("UPDATE approvals SET escalated = 1 WHERE request_id = ?");
    $stmt->execute([$request_id]);
}

header("Location: approve_requests.php");
?>