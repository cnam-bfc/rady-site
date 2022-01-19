<?php
include_once('includes/init.php');

try {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $sqlQuery = 'SELECT * FROM RecettesLikes WHERE visible = 1 AND id = :id';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'id' => $_GET['id']
        ]);
        $recettes = $sqlStatement->fetchAll();
        if (count($recettes) == 0) {
            echo ("ERREUR: Recette avec l'id " . $_GET['id'] . " introuvable");
            die();
        }
    } elseif (isset($_GET['search']) && !empty($_GET['search'])) {
        $sqlQuery = 'SELECT * FROM RecettesLikes WHERE visible = 1 AND nom LIKE :search ORDER BY aime DESC';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'search' => '%' . $_GET['search'] . '%'
        ]);
        $recettes = $sqlStatement->fetchAll();
        if (count($recettes) == 0) {
            echo ("ERREUR: Recettes introuvables");
            die();
        }
    } else {
        $sqlQuery = 'SELECT * FROM RecettesLikes WHERE visible = 1 ORDER BY aime DESC';
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
    echo (displayKeyValue($recette, 'idAuteur', $separator, true));
    if ($nb != 0)
        echo (displayKeyValue($recette, 'aime', $separator, true));
    else
        echo (displayKeyValue($recette, 'aime', $separator, false));
}
