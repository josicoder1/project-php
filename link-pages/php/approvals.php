<?php
session_start();
require_once 'config.php';
if (!in_array($_SESSION['role'], ['manager', 'admin'])) header("Location: dashboard.php");

if (isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $comments = $_POST['comments'];
    $escalated = isset($_POST['escalate']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO approvals (request_id, manager_id, action, comments, escalated) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$request_id, $_SESSION['user_id'], $action, $comments, $escalated]);

    $stmt = $pdo->prepare("UPDATE transport_requests SET status = ? WHERE id = ?");
    $stmt->execute([$action, $request_id]);

    header("Location: approvals.php");
}

$stmt = $pdo->query("SELECT tr.*, u.username FROM transport_requests tr JOIN users u ON tr.submitted_by = u.id WHERE tr.status = 'pending'");
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pending Approvals</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Pending Requests</h2>
        <?php foreach ($requests as $request): ?>
            <div class="request">
                <p>Request ID: <?php echo $request['request_id']; ?></p>
                <p>User: <?php echo $request['username']; ?></p>
                <p>Purpose: <?php echo $request['purpose']; ?></p>
                <p>Date: <?php echo $request['request_date']; ?> <?php echo $request['request_time']; ?></p>
                <form method="POST">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <textarea name="comments" placeholder="Comments"></textarea>
                    <label><input type="checkbox" name="escalate"> Escalate</label>
                    <button type="submit" name="action" value="approved">Approve</button>
                    <button type="submit" name="action" value="rejected">Reject</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>