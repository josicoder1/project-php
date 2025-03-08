<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);

    // File upload handling
    $uploadedFiles = [];
    if (!empty($_FILES['document']['name'][0])) {
        $targetDir = "uploads/";
        foreach ($_FILES['document']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['document']['name'][$key]);
            $targetFilePath = $targetDir . uniqid() . '_' . $fileName;

            if (move_uploaded_file($tmp_name, $targetFilePath)) {
                $uploadedFiles[] = $targetFilePath;
            }
        }
    }

    // Insert into database
    $documents = implode(',', $uploadedFiles);
    $sql = "INSERT INTO transport_requests 
            (purpose, request_date, request_time, destination, documents)
            VALUES ('$purpose', '$date', '$time', '$destination', '$documents')";

    if ($conn->query($sql) === TRUE) {
        echo "Request submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>