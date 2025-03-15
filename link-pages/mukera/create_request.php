<?php
include 'config.php';
if ($_SESSION['role'] !== 'employee') header("Location: dashboard.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>New Transport Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <form method="POST" enctype="multipart/form-data">
            <h2>New Transport Request</h2>

            <div class="mb-3">
                <label>Purpose</label>
                <input type="text" name="purpose" class="form-control" required>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label>Request Date</label>
                    <input type="date" name="request_date" class="form-control" required>
                </div>
                <div class="col">
                    <label>Request Time</label>
                    <input type="time" name="request_time" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Destination</label>
                <input type="text" name="destination" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Supporting Document</label>
                <input type="file" name="document" class="form-control">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit Request</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            // File Upload
            $document_path = 'uploads/' . uniqid() . '_' . basename($_FILES['document']['name']);
            move_uploaded_file($_FILES['document']['tmp_name'], $document_path);

            // Create Request
            $request_id = 'REQ-' . strtoupper(uniqid());
            $stmt = $pdo->prepare("INSERT INTO transport_requests 
                (request_id, purpose, request_date, request_time, destination, submitted_by, document_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $request_id,
                $_POST['purpose'],
                $_POST['request_date'],
                $_POST['request_time'],
                $_POST['destination'],
                $_SESSION['user_id'],
                $document_path
            ]);

            echo "<div class='alert alert-success mt-3'>Request submitted! ID: $request_id</div>";
        }
        ?>
    </div>
</body>

</html>