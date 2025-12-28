<?php
// index.php

// Error Reporting (A desactiver en prod)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload / Core inclusions
require_once 'core/Database.php';
require_once 'core/Auth.php';

// Session Start (Global)
Auth::startSession();

// Routing Simple
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Helpers de vue
function view($path, $data = []) {
    global $page; // Import $page from global scope so header.php can use it
    extract($data);
    require "views/{$path}.php";
}

// Route Switcher
switch ($page) {
    case 'login':
        // Si dejà connecté, redirect
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
             if ($_SESSION['role'] === 'admin') header("Location: ?page=admin_dashboard");
             else header("Location: ?page=student_dashboard");
             exit;
        }
        view('auth/login');
        break;

    case 'logout':
        Auth::logout();
        break;

    case 'check_login':
        // Traitement du formulaire de login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            
            $result = Auth::login($user, $pass);
            
            if ($result['success']) {
                header("Location: " . $result['redirect']);
                exit;
            } else {
                // Retour au login avec erreur
                view('auth/login', ['error' => $result['message']]);
            }
        } else {
            header("Location: ?page=login");
        }
        break;

    // --- STUDENT ROUTES ---
    case 'student_dashboard':
        Auth::check();
        // Verif role student (optionnel si Auth::check suffit)
        if (Auth::isAdmin()) header("Location: ??page=admin_dashboard");
        require 'controllers/student/dashboard.php';
        break;

    case 'select_route':
        Auth::check();
        require 'controllers/student/select_route.php';
        break;

    // --- ADMIN ROUTES ---
    case 'admin_dashboard':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        view('admin/dashboard'); 
        break;
        
    case 'admin_drivers':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        require 'controllers/admin/drivers.php';
        break;

    case 'admin_vehicles':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        require 'controllers/admin/vehicles.php';
        break;

    case 'admin_routes':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        require 'controllers/admin/routes.php';
        break;

    case 'admin_assignments':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        require 'controllers/admin/assignments.php';
        break;

    case 'admin_students':
        Auth::check();
        if (!Auth::isAdmin()) die("Accès refusé.");
        require 'controllers/admin/students.php';
        break;

    // Default
    default:
        header("Location: ?page=login");
        break;
}
?>
