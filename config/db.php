<?php
// config/db.php

// Identifiants de connexion Oracle
// A modifier selon votre configuration locale XAMPP/Oracle
define('DB_USERNAME', 'system');
define('DB_PASSWORD', 'oracle');
define('DB_CONNECTION_STRING', 'localhost/XE');

// Configuration du jeu de caractères
define('DB_CHARSET', 'AL32UTF8');

// Vérification de l'extension OCI8
if (!extension_loaded('oci8')) {
    die("Erreur Critique : L'extension PHP OCI8 n'est pas activée. Veuillez l'activer dans php.ini pour communiquer avec Oracle Database.");
}
?>
