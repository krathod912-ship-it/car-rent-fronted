<?php
// One-time setup: creates users table. Run from browser or CLI.
// Delete or restrict access after use.

$pdo = require __DIR__ . '/admin/db.php';
if (!$pdo) {
    die('Database connection failed. Check admin/db.php configuration.');
}

$pdo->exec("CREATE DATABASE IF NOT EXISTS car_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        address TEXT NULL,
        license_no VARCHAR(100) NULL,
        profile_image VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$tableName = 'users';
$databaseName = $pdo->query('SELECT DATABASE()')->fetchColumn();
$columnsToEnsure = [
    'address' => "ALTER TABLE users ADD COLUMN address TEXT NULL",
    'license_no' => "ALTER TABLE users ADD COLUMN license_no VARCHAR(100) NULL",
    'profile_image' => "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) NULL",
];

foreach ($columnsToEnsure as $columnName => $alterSql) {
    $columnStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM information_schema.columns
        WHERE table_schema = :schema
          AND table_name = :table_name
          AND column_name = :column_name
    ");
    $columnStmt->execute([
        ':schema' => $databaseName,
        ':table_name' => $tableName,
        ':column_name' => $columnName,
    ]);
    $exists = (int) $columnStmt->fetchColumn() > 0;
    if (!$exists) {
        $pdo->exec($alterSql);
    }
}

// Admin users table (optional, for future admin auth features).
$pdo->exec("
    CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Vehicles catalog (seeded once).
$pdo->exec("
    CREATE TABLE IF NOT EXISTS vehicles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        category ENUM('city','family','luxury') NOT NULL,
        transmission ENUM('manual','automatic') NOT NULL,
        fuel ENUM('petrol','diesel','electric') NOT NULL,
        seats INT NOT NULL,
        price_per_day INT NOT NULL,
        image_path VARCHAR(255) NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_vehicle_name (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Bookings placed by users.
$pdo->exec("
    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        vehicle_id INT NOT NULL,
        pickup_location VARCHAR(255) NOT NULL,
        drop_location VARCHAR(255) NOT NULL,
        pickup_datetime DATETIME NOT NULL,
        drop_datetime DATETIME NOT NULL,
        pickup_type ENUM('self_drive','with_driver') NOT NULL,
        customer_full_name VARCHAR(150) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        customer_license_no VARCHAR(100) NOT NULL,
        status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_bookings_user (user_id),
        INDEX idx_bookings_vehicle (vehicle_id),
        CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_bookings_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Payments for bookings (UPI mock).
$pdo->exec("
    CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT NOT NULL,
        method ENUM('google_pay','phonepe','paytm') NOT NULL,
        days INT NOT NULL,
        amount INT NOT NULL,
        status ENUM('initiated','success','failed') NOT NULL DEFAULT 'success',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_payments_booking (booking_id),
        CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Seed vehicles if table is empty.
$countVehicles = (int) $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
if ($countVehicles === 0) {
    $seed = [
        ['Toyota Innova', 'family', 'automatic', 'diesel', 7, 3500, 'assets/images/toyota-innova.jpg'],
        ['Mahindra XUV700', 'family', 'automatic', 'diesel', 7, 4200, 'assets/images/mahindra-xuv700.jpg'],
        ['Kia Carens', 'family', 'automatic', 'petrol', 7, 3800, 'assets/images/kia-carens.jpg'],
        ['Tata Nexon', 'city', 'automatic', 'petrol', 5, 2500, 'assets/images/tata-nexon.jpg'],
        ['Maruti Baleno', 'city', 'manual', 'petrol', 5, 1800, 'assets/images/maruti-baleno.jpg'],
        ['Hyundai i20', 'city', 'automatic', 'petrol', 5, 2200, 'assets/images/hyundai-i20.jpg'],
        ['BMW X5', 'luxury', 'automatic', 'petrol', 5, 8999, 'assets/images/bmw-x5.jpg'],
        ['Mercedes C-Class', 'luxury', 'automatic', 'diesel', 5, 7500, 'assets/images/mercedes-c-class.jpg'],
        ['Audi A4', 'luxury', 'automatic', 'petrol', 5, 6999, 'assets/images/audi-a4.jpg'],
    ];
    $ins = $pdo->prepare("INSERT INTO vehicles (name, category, transmission, fuel, seats, price_per_day, image_path) VALUES (:name,:cat,:tr,:fuel,:seats,:ppd,:img)");
    foreach ($seed as $v) {
        $ins->execute([
            ':name' => $v[0],
            ':cat' => $v[1],
            ':tr' => $v[2],
            ':fuel' => $v[3],
            ':seats' => $v[4],
            ':ppd' => $v[5],
            ':img' => $v[6],
        ]);
    }
}

echo "Setup completed successfully.\n";
echo "Ensured tables: users, admins, vehicles, bookings, payments.\n";
echo "Delete or restrict access to this file after setup.\n";
