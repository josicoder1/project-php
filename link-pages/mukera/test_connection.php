<?php
include 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM users LIMIT 1");
    echo "Connected to store_database_system successfully! 🎉";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>