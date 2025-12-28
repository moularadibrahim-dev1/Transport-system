<?php
// v2_react/api/routes.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'config/database.php';
$db = (new Database())->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query("SELECT * FROM routes");
    $routes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Decode JSON columns for frontend
    foreach($routes as &$r) {
        $r['stops'] = json_decode($r['stops']);
        $r['schedule'] = json_decode($r['schedule']);
    }
    
    echo json_encode($routes);
}
?>
