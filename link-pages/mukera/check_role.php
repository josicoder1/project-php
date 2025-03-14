<?php
function checkRole($allowed_roles) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: dashboard.php");
        exit();
    }
}

// Usage in files:
// checkRole(['admin']); // Only admin can access
// checkRole(['manager', 'admin']); // Both roles can access
?>