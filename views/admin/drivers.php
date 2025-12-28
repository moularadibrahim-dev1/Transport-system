<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3>Ajouter un Chauffeur</h3>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="?page=admin_drivers" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label>Nom complet</label>
            <input type="text" name="name" class="form-control" required placeholder="Ex: Ahmed Benali">
        </div>
        
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label>Téléphone</label>
            <input type="text" name="phone" class="form-control" required placeholder="06...">
        </div>
        
        <button type="submit" class="btn" style="width: auto;">Ajouter</button>
    </form>
</div>

<div class="card">
    <h3>Liste des 12 Chauffeurs</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($drivers as $driver): ?>
            <tr>
                <td><?= htmlspecialchars($driver['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($driver['phone'] ?? 'N/A') ?></td>
                <td><span class="badge badge-bus">Actif</span></td>
                <td>
                    <form action="?page=admin_drivers" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="key" value="<?= $driver['soda_key'] ?>">
                        <button type="submit" style="background:none; border:none; color:var(--danger); cursor:pointer;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($drivers)): ?>
                <tr><td colspan="4" style="text-align:center;">Aucun chauffeur trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer.php'; ?>
