<?php require 'views/layouts/header.php'; ?>

<div class="card" style="text-align: center; border-top: 5px solid var(--secondary-color);">
    <h2>👋 Bienvenue, configurez votre transport</h2>
    <p>Pour accéder à votre espace, vous devez obligatoirement sélectionner votre ligne de transport.</p>
</div>

<form action="?page=select_route" method="POST">
    <div class="route-grid">
        <?php foreach($routes as $r): ?>
        <label class="route-card" style="display:block; position:relative;">
            <input type="radio" name="route_id" value="<?= $r['route_id'] ?>" style="position:absolute; top:1rem; right:1rem; transform:scale(1.5);">
            
            <h3 style="color:var(--primary-color);"><?= htmlspecialchars($r['name']) ?></h3>
            <p style="margin: 1rem 0;">
                <?php 
                    $stops = is_array($r['stops']) ? implode(' <br>↓<br> ', $r['stops']) : $r['stops']; 
                    echo $stops;
                ?>
            </p>
            <div style="background:#eee; padding:0.5rem; border-radius:4px; font-size:0.9rem;">
                <strong>Départ:</strong> <?= $r['schedule']['morning'] ?? '07:30' ?>
            </div>
        </label>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <button type="submit" class="btn" style="width: auto; padding: 1rem 3rem; font-size: 1.2rem;">Confirmer mon Trajet</button>
    </div>
</form>

<?php require 'views/layouts/footer.php'; ?>
