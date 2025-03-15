<?php
require 'db_config.php';
require 'request_id_generator.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Generate a unique request ID
        $request_id = generateRequestID($pdo);

        // Capture user inputs
        $email = $_POST['email'];
        $purpose = $_POST['purpose'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $destination = $_POST['destination'];

        // Start a database transaction
        $pdo->beginTransaction();

        // Insert a new record into the transport_requests table
        $stmt = $pdo->prepare("INSERT INTO transport_requests 
            (request_id, purpose, request_date, request_time, destination, email, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$request_id, $purpose, $date, $time, $destination, $email]);

        // Commit the transaction
        $pdo->commit();

        // Redirect to the success page with the generated request ID
        header("Location: success_page.php?id=" . urlencode($request_id));
        exit();
    } catch (PDOException $e) {
        // Roll back the transaction in case of error
        $pdo->rollBack();

        // Check for duplicate entry error
        if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
            static $retryCount = 0;

            // Retry up to 3 times
            if ($retryCount < 3) {
                $retryCount++;
                // Add a delay before retrying
                usleep(100000 * $retryCount); // 100ms, 200ms, 300ms
                retry_submission($pdo, $_POST);
            } else {
                error_log("Duplicate ID collision: " . $request_id);
                show_error("Request submission failed due to a duplicate ID. Please try again.");
            }
        } else {
            handle_database_error($e);
        }
    } catch (Exception $e) {
        handle_general_error($e);
    }
}

// Function to handle retries
function retry_submission($pdo, $postData)
{
    // You can call the original submission logic here
    $_POST = $postData;
}

// Function to display error messages to the user
function show_error($message)
{
    echo "<div class='error'>$message</div>";
}

// Function to handle database errors
function handle_database_error($e)
{
    error_log("Database Error: " . $e->getMessage());
    show_error("A database error occurred. Please try again later.");
}

// Function to handle general errors
function handle_general_error($e)
{
    error_log("General Error: " . $e->getMessage());
    show_error("An unexpected error occurred. Please try again later.");
}
?>