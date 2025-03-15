<?php
function sendStatusEmail($request_id, $email, $status, $comments)
{
    $subject = "Request $status: $request_id";
    $message = "Your transport request $request_id has been $status.\n";
    $message .= "Comments: $comments\n\n";
    $message .= "Thank you for using our service!";

    $headers = "From: transport@yourcompany.com";

    mail($email, $subject, $message, $headers);
}
?>