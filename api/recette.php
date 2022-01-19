<?php
include_once('init.php');

try {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1 AND id = :id';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'id' => $_GET['id']
        ]);
        $recettes = $sqlStatement->fetchAll();
        if (count($recettes) == 0) {
            echo ("ERREUR: Recette avec l'id " . $_GET['id'] . " introuvable");
            die();
        }
    } else {
        $sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute();
        $recettes = $sqlStatement->fetchAll();
        if (count($recettes) == 0) {
            echo ("ERREUR: Recettes introuvables");
            die();
        }
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

$nb = count($recettes);
foreach ($recettes as $recette) {
    $nb--;
    echo ('recette_' . $recette['id'] . $separator);
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
    if ($nb != 0)
        echo (displayKeyValue($recette, 'idAuteur', $separator, true));
    else
        echo (displayKeyValue($recette, 'idAuteur', $separator, false));
}
