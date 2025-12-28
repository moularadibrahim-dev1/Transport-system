<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3>Ajouter un Véhicule</h3>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="?page=admin_vehicles" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label>Matricule</label>
            <input type="text" name="matricule" class="form-control" required placeholder="1234-A-1">
        </div>
        
        <div class="form-group" style="flex: 1; min-width: 100px; margin-bottom: 0;">
            <label>Capacité</label>
            <input type="number" name="capacity" class="form-control" required value="50">
        </div>

        <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="Bus">Bus</option>
                <option value="Minibus">Minibus</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="is_internat" id="internat">
            <label for="internat" style="margin: 0; cursor: pointer;">Réservé Internat</label>
        </div>
        
        <button type="submit" class="btn" style="width: auto;">Ajouter</button>
    </form>
</div>

<div class="card">
    <h3>Parc de Transport (12 Véhicules)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Capacité</th>
                <th>Type</th>
                <th>Spécial</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($vehicles as $v): ?>
            <tr>
                <td><?= htmlspecialchars($v['matricule'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($v['capacity'] ?? 0) ?></td>
                <td><?= htmlspecialchars($v['type'] ?? 'Bus') ?></td>
                <td>
                    <?php if(!empty($v['is_internat']) && $v['is_internat']): ?>
                        <span class="badge badge-internat">Internat</span>
                    <?php else: ?>
                        <span class="badge badge-bus">Standard</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="?page=admin_vehicles" method="POST" onsubmit="return confirm('Supprimer ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="key" value="<?= $v['soda_key'] ?>">
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
