<?php
require 'db_config.php';

// Create sample users
$users = [
    [
        'username' => 'admin',
        'password' => 'SecureAdmin123!',
        'role' => 'admin'
    ],
    [
        'username' => 'manager',
        'password' => 'StrongManager456!',
        'role' => 'manager'
    ]
];

try {
    foreach ($users as $user) {
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$user['username'], $hashedPassword, $user['role']]);
    }
    echo "Users created successfully!";
} catch (PDOException $e) {
    die("Error creating users: " . $e->getMessage());
}
?>