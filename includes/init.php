<?php
// Code obligatoire, doit s'exécuter avant tout code html ou php
session_start();
?>

<?php
// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';charset=utf8', $_ENV['MYSQL_USERNAME'], $_ENV['MYSQL_PASSWORD']);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur de connexion à la base de données:</br>' . $e->getMessage();
    include_once('includes/error.php');
}
?>