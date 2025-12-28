<?php
// controllers/admin/drivers.php

$message = "";

// --- ACTIONS POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $id = "D" . time(); 
        
        $docData = [
            "driver_id" => $id,
            "name" => $name,
            "phone" => $phone,
            "status" => "active"
        ];
        
        if(Database::insert('drivers', $docData)) $message = "Chauffeur ajouté avec succès.";
        else $message = "Erreur lors de l'ajout.";
    }
    
    if ($action === 'delete') {
        $key = $_POST['key'] ?? ''; 
        if($key) {
            Database::delete('drivers', $key);
            $message = "Chauffeur supprimé.";
        }
    }
}

// --- RECUPERATION LISTE ---
$drivers = Database::findAll('drivers');

// --- APPEL DE LA VUE ---
$data = ['drivers' => $drivers, 'message' => $message];
view('admin/drivers', $data);
?>
