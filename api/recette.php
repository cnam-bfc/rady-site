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
    $sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1 AND id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $id
    ]);
    $recettes = $sqlStatement->fetchAll();
    if (count($recettes) == 0) {
        echo ("ERREUR: Recette avec l'id " . $id . " introuvable");
        die();
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

foreach ($recettes as $recette) {
}

echo (displayKeyValue($recette, 'id', $separator, true));
echo (displayKeyValue($recette, 'nom', $separator, true));
echo (displayKeyValue($recette, 'description', $separator, true));
echo (displayKeyValue($recette, 'date', $separator, true));
echo (displayKeyValue($recette, 'visible', $separator, true));
echo (displayKeyValue($recette, 'imageUrl', $separator, true));
echo (displayKeyValue($recette, 'quantite', $separator, true));
echo (displayKeyValue($recette, 'difficulte', $separator, true));
echo (displayKeyValue($recette, 'tempsPreparation', $separator, true));
echo (displayKeyValue($recette, 'tempsConservation', $separator, true));
echo (displayKeyValue($recette, 'unite', $separator, true));
echo (displayKeyValue($recette, 'idAuteur', $separator, false));
