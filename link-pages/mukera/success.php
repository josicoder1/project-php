<?php
require 'db_config.php';

// Get and sanitize request ID
$request_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

// Fetch request details
$request = null;
if ($request_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM transport_requests WHERE request_id = ?");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Request Submitted</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="success-container">
        <?php if ($request): ?>
            <div class="success-icon">✓</div>
            <h1 class="success-title">Request Submitted Successfully!</h1>

            <div class="request-id"><?= htmlspecialchars($request_id) ?></div>

            <div class="details-box">
                <div class="detail-item">
                    <strong>Email:</strong>
                    <?= htmlspecialchars($request['email']) ?>
                </div>

                <div class="detail-item">
                    <strong>Request Date:</strong>
                    <?= htmlspecialchars($request['request_date']) ?>
                </div>

                <div class="detail-item">
                    <strong>Purpose:</strong>
                    <?= htmlspecialchars($request['purpose']) ?>
                </div>
            </div>

            <a href="public_request.php" class="new-request-btn">New Request</a>

        <?php else: ?>
            <div class="error-notification">
                <h2>Request Not Found</h2>
                <p>Invalid request ID provided.</p>
                <a href="public_request.php" class="new-request-btn">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>