<?php
// controllers/admin/students.php

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $data = [
            "username" => $username,
            "password_hash" => password_hash($password, PASSWORD_DEFAULT),
            "role" => "student",
            "created_at" => date('c'),
            "profile_completed" => false
        ];
        
        if(Database::insert('users', $data)) {
            $message = "Etudiant créé avec succès.";
        } else {
            $message = "Erreur.";
        }
    }
    
    if ($action === 'delete') {
        $key = $_POST['key'];
        Database::delete('users', $key);
        $message = "Compte supprimé.";
    }
}

// Listing users where role = student
// Notre Polyfill 'simpleFindOne' ne supporte pas findAll with filter pour l'instant de façon avancée
// Mais on peut faire findAll et filtrer en PHP (petit volume)
// Ou améliorer simpleFindAll pour accepter un filtre (TODO dans future v2)
// Pour l'instant on filtre en PHP.

$allUsers = Database::findAll('users');
$students = [];
foreach($allUsers as $u) {
    if(isset($u['role']) && $u['role'] === 'student') {
        $students[] = $u;
    }
}

view('admin/students', compact('students', 'message'));
?>
