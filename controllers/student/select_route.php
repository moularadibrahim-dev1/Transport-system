<?php
// controllers/student/select_route.php

$userId = $_SESSION['user_id'];

// Get User
$userData = Database::findOne('users', ["username" => $userId]);
$userKey = $userData['soda_key'] ?? null;

if (!empty($userData['selected_route_id'])) {
    // optional redirect
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedRouteId = $_POST['route_id'];
    
    if($selectedRouteId && $userKey) {
        $userData['selected_route_id'] = $selectedRouteId;
        $userData['updated_at'] = date('c');
        
        // Use Update wrapper
        if(Database::update('users', $userKey, $userData)) {
            header("Location: ?page=student_dashboard");
            exit;
        } else {
            $error = "Erreur technique lors de la sauvegarde.";
        }
    }
}

$routes = Database::findAll('routes');

view('student/select_route', compact('routes'));
?>
