<?php
// controllers/student/dashboard.php

// 1. Récupérer l'utilisateur courant
$userId = $_SESSION['user_id']; 

// Recherche du profil user
$user = Database::findOne('users', ["username" => $userId]);

if (!$user) {
    die("Erreur profil utilisateur introuvable.");
}

// 2. Vérifier si un trajet est sélectionné
if (empty($user['selected_route_id'])) {
    header("Location: ?page=select_route");
    exit;
}

// 3. Récupérer les infos du trajet
$routeId = $user['selected_route_id'];
$route = Database::findOne('routes', ["route_id" => $routeId]);

// 4. Récupérer l'affectation
// Attention: findOne utilise le filtre. Notre assignement a route_id.
$assignment = Database::findOne('assignments', ["route_id" => $routeId]);

if ($assignment) {
    // Bus
    $vehicle = Database::findOne('vehicles', ["vehicle_id" => $assignment['vehicle_id']]);
    $assignment['vehicle'] = $vehicle ? $vehicle : ['matricule' => 'Inconnu', 'type' => 'N/A', 'capacity' => '?'];
    
    // Chauffeur
    $driver = Database::findOne('drivers', ["driver_id" => $assignment['driver_id']]);
    $assignment['driver'] = $driver ? $driver : ['name' => 'Inconnu', 'phone' => 'N/A'];
}

view('student/dashboard', compact('user', 'route', 'assignment'));
?>
