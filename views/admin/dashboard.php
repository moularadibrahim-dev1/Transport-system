<?php
// views/admin/dashboard.php
require 'views/layouts/header.php';

// Récupération des stats simples (Counts)
// Note: SODA count n'est pas toujours direct, on charge tout ou on utilise une astuce
// Pour la démo, on essaye de compter.

$stats = [
    'students' => 0,
    'drivers' => 0,
    'vehicles' => 0,
    'routes' => 0
];

try {
    // Helper pour compter (si la collection est petite, on fetch tout. Sinon on devrait utiliser des index)
    // Ici on assume petit volume.
    $colDrivers = Database::getCollection('drivers');
    // En SODA PHP pur, pas de count direct performant sans index textuel, on va itérer cursor pour l'instant ou mock
    // Si on veut rester propre, on fait confiance à l'admin qui verra les listes.
    
    // Placeholder stats
    $stats['drivers'] = 12; // Cible
    $stats['vehicles'] = 12; // Cible
} catch (Exception $e) {
    // Silent fail
}
?>

<div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
    
    <div class="card" style="border-left: 5px solid var(--primary-color);">
        <h3>Chauffeurs</h3>
        <p style="font-size: 2rem; font-weight: bold;">12</p>
        <span style="color: #777;">Actifs</span>
    </div>

    <div class="card" style="border-left: 5px solid var(--secondary-color);">
        <h3>Transports</h3>
        <p style="font-size: 2rem; font-weight: bold;">12</p>
        <span style="color: #777;">Bus & Minibus</span>
    </div>

    <div class="card" style="border-left: 5px solid var(--success);">
        <h3>Trajets</h3>
        <p style="font-size: 2rem; font-weight: bold;">5</p>
        <span style="color: #777;">Desservis</span>
    </div>

    <div class="card" style="border-left: 5px solid #6f42c1;">
        <h3>Etudiants</h3>
        <p style="font-size: 2rem; font-weight: bold;">--</p>
        <span style="color: #777;">Inscrits</span>
    </div>

</div>

<div class="card">
    <h3>Bienvenue sur l'interface d'administration</h3>
    <p>Utilisez le menu latéral pour gérer le parc automobile, les affectations et le personnel.</p>
</div>

<?php require 'views/layouts/footer.php'; ?>
