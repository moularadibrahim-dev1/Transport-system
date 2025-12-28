<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3><i class="fa fa-ticket-alt"></i> Votre Transport Actuel</h3>
    
    <?php if($route): ?>
        <h1 class="primary-text" style="margin-bottom: 0.5rem;"><?= htmlspecialchars($route['name']) ?></h1>
        <p><strong>Arrêts :</strong> <?= is_array($route['stops']) ? implode(', ', $route['stops']) : $route['stops'] ?></p>
        
        <div class="dashboard-grid">
             <div class="info-box">
                 <h4><i class="fa fa-clock"></i> Horaires</h4>
                 <ul style="list-style:none; margin-top:0.5rem;">
                     <li>Matin: <strong><?= $route['schedule']['morning'] ?? 'N/A' ?></strong></li>
                     <li>Soir: <strong><?= $route['schedule']['evening'] ?? 'N/A' ?></strong></li>
                 </ul>
             </div>

             <?php if($assignment): ?>
                 <div class="info-box highlight">
                     <h4><i class="fa fa-bus"></i> Transport</h4>
                     <p class="text-lg">Matricule: <strong><?= $assignment['vehicle']['matricule'] ?></strong></p>
                     <p>Capacité: <?= $assignment['vehicle']['capacity'] ?> places</p>
                 </div>
                 
                 <div class="info-box warning">
                     <h4><i class="fa fa-id-badge"></i> Chauffeur</h4>
                     <p class="text-lg"><?= $assignment['driver']['name'] ?></p>
                     <p><i class="fa fa-phone"></i> <?= $assignment['driver']['phone'] ?></p>
                 </div>
             <?php else: ?>
                 <div class="info-box warning" style="flex:2; display:flex; align-items:center; justify-content:center;">
                     <p><i class="fa fa-exclamation-triangle"></i> Aucun véhicule n'a encore été attribué à ce trajet. Veuillez patienter ou contacter l'administration.</p>
                 </div>
             <?php endif; ?>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="?page=select_route" class="btn" style="width: auto; background-color: var(--text-light);">Modifier mon trajet</a>
        </div>

    <?php else: ?>
        <p>Erreur: Trajet introuvable.</p>
    <?php endif; ?>
</div>

<?php require 'views/layouts/footer.php'; ?>
