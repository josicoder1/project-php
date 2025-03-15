<?php
session_start();
require_once 'db_config.php';

// Initialize variables
$request = [];
$request_id = null;

// Check if the request ID is provided in the query string
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Fetch the request from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM transport_requests WHERE request_id = ?");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Request Submitted</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .success-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .success-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .request-id {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .details-box {
            text-align: left;
            margin-bottom: 20px;
        }

        .detail-item {
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }

        .new-request-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .new-request-btn:hover {
            background-color: #218838;
        }

        .error-notification {
            text-align: center;
            color: #721c24;
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
        }

        .error-notification h2 {
            margin-top: 0;
        }

        .error-notification p {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="success-container">
        <?php if ($request): ?>
            <!-- Display success message if request data is found -->
            <div class="success-icon">✓</div>
            <h1 class="success-title">Request Submitted Successfully!</h1>

            <div class="request-id">Request ID: <?= htmlspecialchars($request_id) ?></div>

            <div class="details-box">
                <div class="detail-item">
                    <strong>Email:</strong>
                    <?= htmlspecialchars($request['email']) ?>
                </div>

                <div class="detail-item">
                    <strong>Purpose:</strong>
                    <?= htmlspecialchars($request['purpose']) ?>
                </div>

                <div class="detail-item">
                    <strong>Date Needed:</strong>
                    <?= htmlspecialchars($request['request_date']) ?>
                </div>

                <div class="detail-item">
                    <strong>Time Needed:</strong>
                    <?= htmlspecialchars($request['request_time']) ?>
                </div>

                <div class="detail-item">
                    <strong>Destination:</strong>
                    <?= htmlspecialchars($request['destination']) ?>
                </div>
            </div>

            <a href="public_request.php" class="new-request-btn">New Request</a>

        <?php else: ?>
            <!-- Display error message if request data is not found -->
            <div class="error-notification">
                <h2>Request Not Found</h2>
                <p>Invalid request ID provided.</p>
                <a href="public_request.php" class="new-request-btn">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>