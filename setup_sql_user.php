<?php
// setup_sql_user.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'config/db.php';
require_once 'core/Database.php';

echo "<h1>Création d'un utilisateur SQL Dédié</h1>";

// 1. Connexion via PHP (puisque ça marche)
$conn = Database::connect();
echo "<p class='ok'>✅ Connexion PHP réussie (system).</p>";

// 2. Création de l'utilisateur 'suptech_dev'
$username = "suptech_dev";
$password = "dev123";

$sql_create = "CREATE USER $username IDENTIFIED BY $password";
$sql_grant  = "GRANT CONNECT, RESOURCE, DBA TO $username";
$sql_soda   = "GRANT SODA_APP TO $username"; // Pour le NoSQL

// Drop si existant (pour reset)
$check = @oci_parse($conn, "DROP USER $username CASCADE");
@oci_execute($check);

// Execute Create
$stmt = oci_parse($conn, $sql_create);
if (@oci_execute($stmt)) {
    echo "<p style='color:green'>✅ Utilisateur <strong>$username</strong> créé.</p>";
    
    // Grant permissions
    $stmt_grant = oci_parse($conn, $sql_grant);
    if (oci_execute($stmt_grant)) {
        echo "<p style='color:green'>✅ Droits (DBA, Connect) accordés.</p>";
    }
    
    // Grant SODA (optionnel selon version)
    $stmt_soda = oci_parse($conn, $sql_soda);
    @oci_execute($stmt_soda); 

    echo "<hr>";
    echo "<h3>👇 Vos Nouveaux Identifiants pour SQL Developer 👇</h3>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> $username</li>";
    echo "<li><strong>Password:</strong> $password</li>";
    echo "<li><strong>Role:</strong> Default</li>";
    echo "</ul>";
} else {
    $e = oci_error($stmt);
    echo "<p style='color:red'>❌ Erreur création: " . $e['message'] . "</p>";
}
?>
