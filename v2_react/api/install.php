<?php
// v2_react/api/install.php

// Force create DB if not exists
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `suptech_transport_v2` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'suptech_transport_v2' checked/created.<br>";
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Connect to the specific DB
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

if(!$conn) die("❌ Failed to connect to 'suptech_transport_v2'.");

// SQL Schema
$queries = [
    // Users
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'student') DEFAULT 'student',
        selected_route_id INT NULL,
        profile_completed BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // Drivers
    "CREATE TABLE IF NOT EXISTS drivers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        status VARCHAR(20) DEFAULT 'active'
    )",

    // Vehicles
    "CREATE TABLE IF NOT EXISTS vehicles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        matricule VARCHAR(50) NOT NULL,
        type VARCHAR(50),
        capacity INT,
        is_internat BOOLEAN DEFAULT FALSE
    )",

    // Routes
    // JSON columns for stops and schedule to allow flexibility
    "CREATE TABLE IF NOT EXISTS routes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        stops JSON, 
        schedule JSON
    )",

    // Assignments
    "CREATE TABLE IF NOT EXISTS assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        driver_id INT,
        vehicle_id INT,
        route_id INT,
        FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
        FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
        FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE SET NULL
    )"
];

foreach ($queries as $sql) {
    try {
        $conn->exec($sql);
        echo "✅ Table created/checked.<br>";
    } catch(PDOException $e) {
        echo "❌ Error creating table: " . $e->getMessage() . "<br>";
    }
}

// --- SEEDING ---

// 1. Admin
$adminPass = password_hash("admin123", PASSWORD_DEFAULT);
$checkAdmin = $conn->prepare("SELECT id FROM users WHERE username = ?");
$checkAdmin->execute(['admin@suptech.ma']);
if($checkAdmin->rowCount() == 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute(['admin@suptech.ma', $adminPass]);
    echo "👤 Admin created.<br>";
}

// 2. Drivers
$checkDrivers = $conn->query("SELECT count(*) FROM drivers")->fetchColumn();
if ($checkDrivers == 0) {
    $stmt = $conn->prepare("INSERT INTO drivers (name, phone) VALUES (?, ?)");
    for ($i = 1; $i <= 12; $i++) {
        $stmt->execute(["Chauffeur V2 $i", "06000000$i"]);
    }
    echo "🚌 12 Drivers seeded.<br>";
}

// 3. Vehicles
$checkVehicles = $conn->query("SELECT count(*) FROM vehicles")->fetchColumn();
if ($checkVehicles == 0) {
    $stmt = $conn->prepare("INSERT INTO vehicles (matricule, type, capacity, is_internat) VALUES (?, ?, ?, ?)");
    for ($i = 1; $i <= 12; $i++) {
        $isInternat = ($i <= 2) ? 1 : 0;
        $type = $isInternat ? "Grand Bus" : "Minibus";
        $cap = $isInternat ? 50 : 25;
        $stmt->execute(["V2-MAT-$i", $type, $cap, $isInternat]);
    }
    echo "🚐 12 Vehicles seeded.<br>";
}

// 4. Routes
$checkRoutes = $conn->query("SELECT count(*) FROM routes")->fetchColumn();
if ($checkRoutes == 0) {
    $routesData = [
        ["Mohamed VI", ["Riad Salam", "Majorelle"], ["morning" => "07:30", "evening" => "18:30"]],
        ["Internat", ["Internat", "Suptech"], ["morning" => "07:30", "evening" => "18:30"]],
        ["Gare", ["Gare", "Centre"], ["morning" => "08:00", "evening" => "18:00"]]
    ];
    $stmt = $conn->prepare("INSERT INTO routes (name, stops, schedule) VALUES (?, ?, ?)");
    foreach($routesData as $r) {
        $stmt->execute([$r[0], json_encode($r[1]), json_encode($r[2])]);
    }
    echo "📍 Routes seeded.<br>";
}

echo "<h2>🎉 V2 Installation Complete!</h2>";
?>
