<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3>Inscrire un Etudiant</h3>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="?page=admin_students" method="POST" style="display:flex; gap:1rem; align-items:flex-end;">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group" style="flex:1;">
            <label>Email / Identifiant</label>
            <input type="text" name="username" class="form-control" required placeholder="nom.prenom@suptech.ma">
        </div>
        
        <div class="form-group" style="flex:1;">
            <label>Mot de passe par défaut</label>
            <input type="text" name="password" class="form-control" required value="suptech2025">
        </div>
        
        <button type="submit" class="btn">Créer le compte</button>
    </form>
</div>

<div class="card">
    <h3>Liste des Etudiants</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Identifiant</th>
                <th>Choix Trajet</th>
                <th>Date Création</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['username']) ?></td>
                <td>
                    <?php if(!empty($s['selected_route_id'])): ?>
                        <span class="badge badge-success" style="background:#d4edda; color:#155724;">Fait</span>
                    <?php else: ?>
                        <span class="badge badge-danger" style="background:#f8d7da; color:#721c24;">En attente</span>
                    <?php endif; ?>
                </td>
                <td><?= substr($s['created_at'] ?? '', 0, 10) ?></td>
                <td>
                    <form action="?page=admin_students" method="POST" onsubmit="return confirm('Supprimer ce compte ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="key" value="<?= $s['soda_key'] ?>">
                        <button type="submit" style="background:none; border:none; color:var(--danger); cursor:pointer;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer.php'; ?>
