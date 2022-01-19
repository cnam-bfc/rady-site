<?php
include_once('includes/init.php');

if (
    !isset($_GET['id'])
    || empty($_GET['id'])
) {
    echo ("ERREUR: Aucun id passé en paramètre");
    die();
}

$idRecette = $_GET['id'];

$sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1 AND id = :id';
$sqlStatement = $mysqlClient->prepare($sqlQuery);
$sqlStatement->execute([
    'id' => $idRecette
]);
$recettes = $sqlStatement->fetchAll();
if (count($recettes) == 0) {
    echo ("ERREUR: Recette avec l'id " . $idRecette . " introuvable");
    die();
}

try {
    $sqlQuery = 'SELECT * FROM Etapes WHERE idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $idRecette
    ]);
    $etapes = $sqlStatement->fetchAll();
    if (count($etapes) == 0) {
        echo ("ERREUR: Etapes de la recette avec l'id " . $idRecette . " introuvable");
        die();
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

$nbEtapes = count($etapes);
foreach ($etapes as $etape) {
    $nbEtapes--;
    echo ('etape_' . $etape['id'] . $separator);
    echo (displayKeyValue($etape, 'id', $separator, true));
    echo (displayKeyValue($etape, 'description', $separator, true));
    if ($nbEtapes != 0)
        echo (displayKeyValue($etape, 'idAnimation', $separator, true));
    else
        echo (displayKeyValue($etape, 'idAnimation', $separator, false));
}
