<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3>Ajouter un Trajet</h3>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="?page=admin_routes" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label>Nom du Trajet</label>
            <select name="name" class="form-control">
                <option value="Mohamed VI">Mohamed VI (Riad Salam - Majorelle...)</option>
                <option value="Internat">Internat (Direct)</option>
                <option value="Hassan II">Hassan II</option>
                <option value="Gare">Gare</option>
                <option value="Cité Universitaire">Cité Universitaire</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Arrêts (séparés par des virgules)</label>
            <input type="text" name="stops" class="form-control" placeholder="Riad Salam, Majorelle, Taxi Ait Taki..." required>
        </div>

        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex:1;">
                <label>Départ Matin</label>
                <input type="time" name="time_morning" class="form-control" value="07:30">
            </div>
            <div class="form-group" style="flex:1;">
                <label>Retour Soir</label>
                <input type="time" name="time_evening" class="form-control" value="18:30">
            </div>
        </div>
        
        <button type="submit" class="btn">Créer le Trajet</button>
    </form>
</div>

<div class="card">
    <h3>Trajets Configurés</h3>
    <div class="route-grid">
        <?php foreach($routes as $r): ?>
        <div class="route-card" style="cursor: default;">
            <div style="display:flex; justify-content:space-between;">
                <h4><?= htmlspecialchars($r['name']) ?></h4>
                <form action="?page=admin_routes" method="POST" onsubmit="return confirm('Confirmer suppression ?');">
                     <input type="hidden" name="action" value="delete">
                     <input type="hidden" name="key" value="<?= $r['soda_key'] ?>">
                     <button type="submit" style="border:none; background:none; color:var(--danger); cursor:pointer;"><i class="fa fa-times"></i></button>
                </form>
            </div>
            <p style="color: #666; font-size: 0.9rem; margin: 0.5rem 0;">
                <i class="fa fa-map-marker-alt"></i> <?= implode(' -> ', $r['stops'] ?? []) ?>
            </p>
            <div style="margin-top: 1rem; font-size: 0.85rem; background: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                <i class="fa fa-clock"></i> Matin: <?= $r['schedule']['morning'] ?? 'N/A' ?> <br>
                <i class="fa fa-history"></i> Soir: <?= $r['schedule']['evening'] ?? 'N/A' ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
