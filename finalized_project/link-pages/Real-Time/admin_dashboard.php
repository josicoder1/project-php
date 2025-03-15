<?php
require 'auth.php';
require 'db_config.php';

$section = $_GET['section'] ?? 'pending';

try {
    switch ($section) {
        case 'vehicles':
            $vehicles = $pdo->query("SELECT * FROM vehicles")->fetchAll();
            break;
        case 'reports':
            $reports = $pdo->query("SELECT status, COUNT(*) as count FROM transport_requests GROUP BY status")->fetchAll();
            break;
        default:
            $requests = $pdo->query("
                SELECT tr.*, COALESCE(u.username, 'Guest') as username 
                FROM transport_requests tr
                LEFT JOIN users u ON tr.submitted_by = u.id
                WHERE status = 'pending'
            ")->fetchAll();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Verify path -->
</head>

<body>
    <div class="container">
        <div class="admin-header">
            <h2>Welcome, Admin</h2>
            <nav class="admin-nav">
                <a href="?section=pending" class="<?= $section === 'pending' ? 'active' : '' ?>">Pending Requests</a>
                <a href="?section=vehicles" class="<?= $section === 'vehicles' ? 'active' : '' ?>">Vehicle Status</a>
                <a href="?section=reports" class="<?= $section === 'reports' ? 'active' : '' ?>">Reports</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </nav>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="notification success">Action completed successfully!</div>
        <?php endif; ?>

        <div class="dashboard-content">
            <?php switch ($section):
                case 'vehicles': ?>
                    <h3>Vehicle Management</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Location</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><?= htmlspecialchars($vehicle['vehicle_id']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['type']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['status']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['location']) ?></td>
                                    <td><?= $vehicle['last_updated'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php break; ?>

                <?php
                case 'reports': ?>
                    <h3>Request Statistics</h3>
                    <div class="stats-grid">
                        <?php foreach ($reports as $report): ?>
                            <div class="stat-card <?= strtolower($report['status']) ?>">
                                <h4><?= ucfirst($report['status']) ?></h4>
                                <div class="stat-value"><?= $report['count'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php break; ?>

                <?php
                default: ?>
                    <h3>Pending Requests</h3>
                    <div class="requests-list">
                        <?php if (empty($requests)): ?>
                            <div class="notification info">No pending requests</div>
                        <?php else: ?>
                            <?php foreach ($requests as $request): ?>
                                <div class="request-card">
                                    <p><strong>Request ID:</strong> <?= htmlspecialchars($request['request_id']) ?></p>
                                    <p><strong>Submitted By:</strong> <?= htmlspecialchars($request['username']) ?></p>
                                    <p><strong>Purpose:</strong> <?= htmlspecialchars($request['purpose']) ?></p>
                                    <p><strong>Date:</strong> <?= $request['request_date'] ?></p>
                                    <form method="POST" action="admin_approve.php">
                                        <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                        <div class="form-group">
                                            <textarea name="comments" placeholder="Enter comments..."></textarea>
                                        </div>
                                        <div class="action-buttons">
                                            <button type="submit" name="action" value="approved" class="btn btn-primary">Approve</button>
                                            <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
            <?php endswitch; ?>
        </div>
    </div>
</body>

</html>