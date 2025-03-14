<?php
session_start();

// Redirect to login if not authenticated
function requireAuth()
{
    if (!isset($_SESSION['admin_loggedin'])) { // Added closing parenthesis here
        header("Location: admin_login.php");
        exit();
    }
}

// Check user role
function checkRole($allowedRoles)
{
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("HTTP/1.1 403 Forbidden");
        exit("Access denied");
    }
}
?>