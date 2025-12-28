<?php
// run_plsql.php
// Script utilitaire pour exécuter le SQL via PHP

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'core/Database.php';

echo "<h1>Déploiement du PL/SQL...</h1>";

$conn = Database::connect();

// Lire le fichier SQL
$sqlFile = file_get_contents('plsql_logic.sql');

// Nettoyer et séparer les instructions (C'est un peu "hacky" pour du PL/SQL complexe via PHP simple, 
// mais ça marche pour des CREATE OR REPLACE basiques séparés par /)
// Note: oci_execute ne supporte pas plusieurs commandes d'un coup ni le "/" final de SQLPlus.

// On coupe sur le "/" qui est le séparateur standard dans mon fichier
$statements = explode('/', $sqlFile);

foreach ($statements as $sql) {
    $sql = trim($sql);
    if (empty($sql)) continue;
    
    // Retirer les commentaires -- (simpliste)
    // $sql = preg_replace('/--.*$/m', '', $sql); 
    
    echo "<hr><pre>" . htmlspecialchars(substr($sql, 0, 100)) . "...</pre>";
    
    $stmt = oci_parse($conn, $sql);
    if (@oci_execute($stmt)) {
        echo "<p style='color:green'>✅ Succès</p>";
    } else {
        $e = oci_error($stmt);
        // Ignorer erreurs vides ou de formatage
        if ($e['code'] != 0) {
            echo "<p style='color:red'>❌ Erreur: " . $e['message'] . "</p>";
        }
    }
}

echo "<h2>Terminé !</h2>";
echo "<p><a href='index.php?page=admin_vehicles'>Aller ajouter un véhicule</a></p>";
?>
