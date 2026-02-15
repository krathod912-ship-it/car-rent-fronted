<?php
// CLI helper to create an admin user. Run from command line:
// php create_admin.php username password
// This script is intentionally CLI-only to avoid exposing admin creation on the web.

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

$argvUser = $argv[1] ?? null;
$argvPass = $argv[2] ?? null;

if (!$argvUser || !$argvPass) {
    echo "Usage: php create_admin.php <username> <password>\n";
    exit(1);
}

$username = trim($argvUser);
$password = $argvPass;

// Load DB (returns PDO)
$pdo = require __DIR__ . '/db.php';

// Create table if not exists
$pdo->exec(
    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

// Check existing
$stmt = $pdo->prepare('SELECT id FROM admins WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $username]);
if ($stmt->fetch()) {
    echo "User '{$username}' already exists.\n";
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :hash)');
$insert->execute([':username' => $username, ':hash' => $hash]);

echo "Admin user '{$username}' created successfully.\n";
echo "Delete this script after use to keep the panel secure.\n";
