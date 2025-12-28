<?php require 'views/layouts/header.php'; ?>

<div class="card">
    <h3>Nouvelle Affectation</h3>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="?page=admin_assignments" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label>Trajet</label>
            <select name="route_id" class="form-control" required>
                <option value="">-- Choisir Trajet --</option>
                <?php foreach($routes as $r): ?>
                    <option value="<?= $r['route_id'] ?>"><?= $r['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Transport</label>
            <select name="vehicle_id" class="form-control" required>
                <option value="">-- Choisir Véhicule --</option>
                <?php foreach($vehicles as $v): ?>
                    <option value="<?= $v['vehicle_id'] ?>"><?= $v['matricule'] ?> (<?= $v['type'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Chauffeur</label>
            <select name="driver_id" class="form-control" required>
                <option value="">-- Choisir Chauffeur --</option>
                <?php foreach($drivers as $d): ?>
                    <option value="<?= $d['driver_id'] ?>"><?= $d['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
             <button type="submit" class="btn">Affecter</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Affectations Actives</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Trajet</th>
                <th>Transport</th>
                <th>Chauffeur</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($assignments as $a): ?>
            <tr>
                <td><strong><?= htmlspecialchars($a['route_name']) ?></strong></td>
                <td><?= htmlspecialchars($a['vehicle_matricule']) ?></td>
                <td><?= htmlspecialchars($a['driver_name']) ?></td>
                <td>
                    <form action="?page=admin_assignments" method="POST" onsubmit="return confirm('Retirer affectation ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="key" value="<?= $a['soda_key'] ?>">
                        <button type="submit" style="background:none; border:none; color:var(--danger); cursor:pointer;">
                            <i class="fa fa-times-circle"></i> Retirer
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer.php'; ?>
