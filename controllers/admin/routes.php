<?php
// controllers/admin/routes.php

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        $name = $_POST['name'] ?? '';
        $stopsStr = $_POST['stops'] ?? '';
        $timeMorning = $_POST['time_morning'] ?? '07:30';
        $timeEvening = $_POST['time_evening'] ?? '18:30';
        
        // Convertir stops en array
        $stops = array_map('trim', explode(',', $stopsStr));
        
        $id = "R" . time();
        
        $docData = [
            "route_id" => $id,
            "name" => $name,
            "stops" => $stops,
            "schedule" => [
                "morning" => $timeMorning,
                "evening" => $timeEvening
            ]
        ];
        
        if (Database::insert('routes', $docData)) $message = "Trajet créé.";
        else $message = "Erreur.";
    }
    
    if ($action === 'delete') {
        $key = $_POST['key'] ?? '';
        if($key) {
            Database::delete('routes', $key);
            $message = "Trajet supprimé.";
        }
    }
}

// Fetch all routes
$routes = Database::findAll('routes');

view('admin/routes', ['routes' => $routes, 'message' => $message]);
?>
