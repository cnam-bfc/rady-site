<?php
include_once('init.php');

if (
    !isset($_GET['id'])
    || empty($_GET['id'])
) {
    echo ("ERREUR: Aucun id passé en paramètre");
    die();
}

$id = $_GET['id'];

try {
    $sqlQuery = 'SELECT * FROM Utilisateurs WHERE id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $id
    ]);
    $utilisateurs = $sqlStatement->fetchAll();
    if (count($utilisateurs) == 0) {
        echo ("ERREUR: Utilisateur avec l'id " . $id . " introuvable");
        die();
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

foreach ($utilisateurs as $utilisateur) {
}

echo (displayKeyValue($utilisateur, 'id', $separator, true));
echo (displayKeyValue($utilisateur, 'pseudo', $separator, true));
echo (displayKeyValue($utilisateur, 'email', $separator, true));
echo (displayKeyValue($utilisateur, 'date', $separator, true));
echo (displayKeyValue($utilisateur, 'avatarUrl', $separator, true));
echo (displayKeyValue($utilisateur, 'prenom', $separator, true));
echo (displayKeyValue($utilisateur, 'nom', $separator, false));
