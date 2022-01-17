<?php
// Code obligatoire, doit s'exécuter avant tout code html ou php
session_start();
?>

<?php
include_once('vendor/autoload.php');
?>

<?php
// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=10.254.1.3;dbname=rady;charset=utf8', 'rady', 'yWEFf3ioTM9BHmAa');
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur de connexion à la base de données:</br>' . $e->getMessage();
    include_once('includes/error.php');
}
?>