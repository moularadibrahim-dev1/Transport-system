<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suptech Transport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome (CDN) for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="app-container">
    <nav class="sidebar">
        <div class="sidebar-logo">
            <h3><i class="fa fa-bus"></i> Suptech Trans</h3>
        </div>
        
        <div class="sidebar-menu">
            <?php if (Auth::isAdmin()): ?>
                <a href="?page=admin_dashboard" class="nav-link <?= $page == 'admin_dashboard' ? 'active' : '' ?>">
                    <i class="fa fa-tachometer-alt"></i> Tableau de bord
                </a>
                <a href="?page=admin_drivers" class="nav-link <?= $page == 'admin_drivers' ? 'active' : '' ?>">
                    <i class="fa fa-id-card"></i> Chauffeurs
                </a>
                <a href="?page=admin_vehicles" class="nav-link <?= $page == 'admin_vehicles' ? 'active' : '' ?>">
                    <i class="fa fa-bus-alt"></i> Transports
                </a>
                <a href="?page=admin_routes" class="nav-link <?= $page == 'admin_routes' ? 'active' : '' ?>">
                    <i class="fa fa-map-signs"></i> Trajets
                </a>
                <a href="?page=admin_assignments" class="nav-link <?= $page == 'admin_assignments' ? 'active' : '' ?>">
                    <i class="fa fa-tasks"></i> Affectations
                </a>
                <a href="?page=admin_students" class="nav-link <?= $page == 'admin_students' ? 'active' : '' ?>">
                    <i class="fa fa-users"></i> Etudiants
                </a>
            <?php else: ?>
                <a href="?page=student_dashboard" class="nav-link <?= $page == 'student_dashboard' ? 'active' : '' ?>">
                    <i class="fa fa-home"></i> Mon Trajet
                </a>
            <?php endif; ?>
            
            <a href="?page=logout" class="nav-link" style="margin-top: 2rem;">
                <i class="fa fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </nav>

    <main class="main-content">
        <header class="top-bar">
            <h2>
                <?php 
                    if($page === 'admin_dashboard') echo 'Vue d\'ensemble';
                    elseif($page === 'admin_drivers') echo 'Gestion des Chauffeurs';
                    elseif($page === 'admin_vehicles') echo 'Parc Transport';
                    elseif($page === 'student_dashboard') echo 'Espace Etudiant';
                    else echo 'Gestion Transport';
                ?>
            </h2>
            <div class="user-info">
                <span><i class="fa fa-user-circle"></i> <?= $_SESSION['user_id'] ?? 'Utilisateur' ?></span>
            </div>
        </header>

        <div class="content-wrapper">
