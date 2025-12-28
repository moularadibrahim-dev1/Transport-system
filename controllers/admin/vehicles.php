<?php
// controllers/admin/vehicles.php

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        $matricule = $_POST['matricule'] ?? '';
        $capacity = (int)($_POST['capacity'] ?? 0);
        $type = $_POST['type'] ?? 'Bus';
        $is_internat = isset($_POST['is_internat']);
        $id = "V" . time();
        
        // OPTION PL/SQL : PL/SQL ACTIF
        
        $conn = Database::connect();
        $sql = "BEGIN AJOUT_VEHICULE(:mat, :cap, :typ, :internat); END;";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":mat", $matricule);
        oci_bind_by_name($stmt, ":cap", $capacity);
        oci_bind_by_name($stmt, ":typ", $type);
        $internatVal = $is_internat ? 1 : 0;
        oci_bind_by_name($stmt, ":internat", $internatVal);
        
        if (@oci_execute($stmt)) $message = "Véhicule ajouté via PL/SQL !";
        else {
             $e = oci_error($stmt);
             $message = "Erreur PL/SQL: " . $e['message'];
        }
        

        // OPTION STANDARD (JSON NoSQL)
        /*
        $docData = [
            "vehicle_id" => $id,
            "matricule" => $matricule,
            "capacity" => $capacity,
            "type" => $type,
            "is_internat" => $is_internat
        ];
        
        if (Database::insert('vehicles', $docData)) $message = "Véhicule ajouté.";
        else $message = "Erreur.";
        */
    }
    
    if ($action === 'delete') {
        $key = $_POST['key'] ?? '';
        if($key) {
            Database::delete('vehicles', $key);
            $message = "Véhicule supprimé.";
        }
    }
}

$vehicles = Database::findAll('vehicles');

view('admin/vehicles', ['vehicles' => $vehicles, 'message' => $message]);
?>
