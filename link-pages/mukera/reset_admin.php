<?php
require 'db_config.php';

try {
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$newPassword]);
    echo "Admin password reset to 'admin123'!";
    echo "<br>Delete this file immediately after use!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>