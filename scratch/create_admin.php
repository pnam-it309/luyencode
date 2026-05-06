<?php
/**
 * Script to create a default admin user
 */
use Cake\Datasource\ConnectionManager;
use Cake\Auth\DefaultPasswordHasher;

// Minimal bootstrap to get database connection
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/bootstrap.php';

$username = 'admin';
$password = 'admin123';
$fullName = 'System Administrator';
$role = 'admin';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $connection = ConnectionManager::get('default');
    
    // Check if user already exists
    $exists = $connection->execute("SELECT id FROM users WHERE username = ?", [$username])->fetch();
    
    if ($exists) {
        // Update existing user
        $connection->execute(
            "UPDATE users SET password = ?, full_name = ?, role = ? WHERE username = ?",
            [$hashedPassword, $fullName, $role, $username]
        );
        echo "User 'admin' updated successfully.\n";
    } else {
        // Insert new user
        $connection->execute(
            "INSERT INTO users (username, password, full_name, role, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$username, $hashedPassword, $fullName, $role]
        );
        echo "User 'admin' created successfully.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
