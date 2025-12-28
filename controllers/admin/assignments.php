<?php
// controllers/admin/assignments.php

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        $driver_id = $_POST['driver_id'];
        $vehicle_id = $_POST['vehicle_id'];
        $route_id = $_POST['route_id'];
        
        $docData = [
            "assignment_id" => "AS" . time(),
            "driver_id" => $driver_id,
            "vehicle_id" => $vehicle_id,
            "route_id" => $route_id,
            "created_at" => date('Y-m-d H:i:s')
        ];
        
        if(Database::insert('assignments', $docData)) {
            $message = "Affectation réussie.";
        }
    }
    
    if ($action === 'delete') {
         $key = $_POST['key'];
         Database::delete('assignments', $key);
         $message = "Affectation supprimée.";
    }
}

$assignments = Database::findAll('assignments');
$drivers = Database::findAll('drivers');
$vehicles = Database::findAll('vehicles');
$routes = Database::findAll('routes');

// Enrich assignments with names (manual join since NoSQL)
foreach($assignments as &$a) {
    $a['driver_name'] = 'N/A';
    foreach($drivers as $d) if(($d['driver_id']??'') == ($a['driver_id']??'')) $a['driver_name'] = $d['name'];
    
    $a['vehicle_matricule'] = 'N/A';
    foreach($vehicles as $v) if(($v['vehicle_id']??'') == ($a['vehicle_id']??'')) $a['vehicle_matricule'] = $v['matricule'];
    
    $a['route_name'] = 'N/A';
    foreach($routes as $r) if(($r['route_id']??'') == ($a['route_id']??'')) $a['route_name'] = $r['name'];
}

view('admin/assignments', compact('assignments', 'drivers', 'vehicles', 'routes', 'message'));
?>
