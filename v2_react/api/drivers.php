<?php
// v2_react/api/drivers.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit; }

require_once 'config/database.php';
$db = (new Database())->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query("SELECT * FROM drivers ORDER BY id DESC");
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($drivers);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name)) {
        $stmt = $db->prepare("INSERT INTO drivers (name, phone) VALUES (?, ?)");
        if($stmt->execute([$data->name, $data->phone ?? ''])) {
            echo json_encode(["message" => "Driver created", "id" => $db->lastInsertId()]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Unable to create driver."]);
        }
    }
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if($id) {
        $stmt = $db->prepare("DELETE FROM drivers WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "Driver deleted"]);
    }
}
?>
