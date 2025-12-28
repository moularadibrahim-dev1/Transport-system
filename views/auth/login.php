<!-- views/auth/login.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Suptech Transport</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <!-- Placeholder logo if exists, or text -->
            <h2>Suptech Transport</h2>
            <p>Portail de gestion du transport universitaire</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="?page=check_login" method="POST">
            <div class="form-group">
                <label for="username">Identifiant / Email</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Etudiant ou Admin" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</div>

</body>
</html>
