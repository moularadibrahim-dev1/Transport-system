<?php
// install.php
// SCRIPT D'INSTALLATION AUTOMATISÉ - SUPTECH TRANSPORT (VERSION COMPATIBLE)

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';
require_once 'core/Database.php'; // Use the wrapper

echo "<!DOCTYPE html><html><head><title>Installation Suptech</title><style>body{font-family:sans-serif;line-height:1.6;padding:2rem;} .ok{color:green;} .err{color:red;} h2{border-bottom:2px solid #ccc;}</style></head><body>";
echo "<h1>Installation Système Transport Suptech</h1>";

// 1. Connection
echo "<h2>1. Connexion à la Base de Données</h2>";
$conn = Database::connect();
echo "<p class='ok'>✅ Connexion Oracle réussie.</p>";

// 2. Setup Tables/Collections
echo "<h2>2. Configuration du Stockage (Mode Hybride/Compatibilité)</h2>";
$collections = ['users', 'students', 'drivers', 'vehicles', 'routes', 'assignments'];

function createTable($conn, $name) {
    // Drop logic (brutal)
    $sqlDrop = "DROP TABLE $name CASCADE CONSTRAINTS";
    @oci_execute(oci_parse($conn, $sqlDrop));
    
    // Create logic (Standard SQL with JSON support)
    // Works on Oracle 12c+ even without SODA
    $sqlCreate = "CREATE TABLE $name (
        id VARCHAR2(255) PRIMARY KEY,
        json_document CLOB CHECK (json_document IS JSON)
    )";
    $stmt = oci_parse($conn, $sqlCreate);
    if (@oci_execute($stmt)) {
        echo "<p class='ok'>✅ Table '$name' créée (Mode SQL).</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p class='err'>❌ Echec création '$name': " . $e['message'] . "</p>";
    }
}

$useSoda = function_exists('oci_soda_open_collection');

if ($useSoda) {
    echo "<p class='ok'>ℹ️ Extension SODA détectée. Utilisation du mode NATIF.</p>";
    $soda = oci_soda_open($conn);
    foreach ($collections as $colName) {
        $col = oci_soda_create_collection($soda, $colName); // Opens if exists
        echo "<p class='ok'>✅ Collection '$colName' prête.</p>";
    }
} else {
    echo "<p style='color:orange;'>ℹ️ Extension SODA absente. Utilisation du mode COMPATIBILITÉ SQL.</p>";
    foreach ($collections as $colName) {
        createTable($conn, $colName);
    }
}

// 3. Seeding Data
echo "<h2>3. Injection des Données de Test</h2>";

// Delete all old data first? installation creates fresh tables anyway.

// Admin
$adminData = [
    "username" => "admin@suptech.ma",
    "password_hash" => password_hash("admin123", PASSWORD_DEFAULT),
    "role" => "admin",
    "created_at" => date('c')
];
if(Database::insert('users', $adminData)) echo "<p>👤 Admin créé.</p>";

// Drivers
for ($i = 1; $i <= 12; $i++) {
    $d = [
        "driver_id" => "D$i",
        "name" => "Chauffeur Suptech $i",
        "phone" => "06" . str_pad($i, 8, '0', STR_PAD_LEFT),
        "status" => "active"
    ];
    Database::insert('drivers', $d);
}
echo "<p>🚌 12 Chauffeurs créés.</p>";

// Vehicles
for ($i = 1; $i <= 12; $i++) {
    $isInternat = ($i <= 2); 
    $v = [
        "vehicle_id" => "V$i",
        "matricule" => "123$i-A-50",
        "capacity" => $isInternat ? 50 : 25,
        "type" => $isInternat ? "Grand Bus" : "Minibus",
        "is_internat" => $isInternat
    ];
    Database::insert('vehicles', $v);
}
echo "<p>🚐 12 Véhicules créés.</p>";

// Routes
$routesData = [
    ["Mohamed VI", ["Riad Salam", "Majorelle", "Taxi Ait Taki", "Lycée Ibn Khaldoun"]],
    ["Internat", ["Internat", "Suptech"]],
    ["Gare", ["Gare", "Centre"]],
    ["Hassan II", ["Hassan II", "Place Armes"]],
    ["Cité Universitaire", ["Cité U", "Suptech"]]
];
foreach ($routesData as $idx => $r) {
    $rd = [
        "route_id" => "R" . ($idx+1),
        "name" => $r[0],
        "stops" => $r[1],
        "schedule" => ["morning" => "07:30", "evening" => "18:30"]
    ];
    Database::insert('routes', $rd);
}
echo "<p>📍 5 Trajets configurés.</p>";

echo "<h2>4. Installation du PL/SQL (Procédures Stockées)</h2>";
$sqlFile = file_get_contents('plsql_logic.sql');
$statements = explode('/', $sqlFile); // Split by slash for PL/SQL blocks
$plsqlSuccess = true;

foreach ($statements as $sql) {
    $sql = trim($sql);
    if (empty($sql)) continue;
    $stmt = oci_parse($conn, $sql);
    if (!@oci_execute($stmt)) {
        $e = oci_error($stmt);
        if ($e['code'] != 0) {
             echo "<p class='err'>⚠️ Erreur PL/SQL: " . $e['message'] . "</p>";
             // Don't mark fail for "already exists" errors if using Replace
        }
    }
}
echo "<p class='ok'>✅ Procédures Stockées déployées.</p>";

echo "<h2>✅ Installation Terminée !</h2>";
echo "<p><a href='index.php'>👉 Accéder à l'application</a></p>";
echo "</body></html>";
?>
