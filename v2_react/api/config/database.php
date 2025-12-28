<?php
// v2_react/api/config/database.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

date_default_timezone_set("Africa/Casablanca");

define('DB_HOST', 'localhost');
define('DB_NAME', 'suptech_transport_v2'); // New DB for V2
define('DB_USER', 'root'); // Standard XAMPP
define('DB_PASS', '');     // Standard XAMPP

class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->conn->exec("set names utf8mb4");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Echo error not ideal for API, better log it.
            // echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
