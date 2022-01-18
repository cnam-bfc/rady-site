<?php
include_once('init.php');

if (
    !isset($_GET['id'])
    || empty($_GET['id'])
) {
    echo ("ERREUR: Aucun id passé en paramètre");
    die();
}

$idRecette = $_GET['id'];

try {
    $sqlQuery = 'SELECT IngredientsRecettes.idIngredient, Ingredients.nom, Ingredients.imageUrl, IngredientsRecettes.quantite, IngredientsRecettes.unite
    FROM IngredientsRecettes, Ingredients
    WHERE IngredientsRecettes.idIngredient = Ingredients.id AND IngredientsRecettes.idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $idRecette
    ]);
    $ingredients = $sqlStatement->fetchAll();
    if (count($ingredients) == 0) {
        echo ("ERREUR: Ingrédients de la recette avec l'id " . $id . " introuvable");
        die();
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

$nbIngredients = count($ingredients);
foreach ($ingredients as $ingredient) {
    $nbIngredients--;
    echo ('ingredient_' . $ingredient['idIngredient'] . $separator);
    echo (displayKeyValue($ingredient, 'idIngredient', $separator, true));
    echo (displayKeyValue($ingredient, 'nom', $separator, true));
    echo (displayKeyValue($ingredient, 'imageUrl', $separator, true));
    echo (displayKeyValue($ingredient, 'quantite', $separator, true));
    if ($nbIngredients != 0)
        echo (displayKeyValue($ingredient, 'unite', $separator, true));
    else
        echo (displayKeyValue($ingredient, 'unite', $separator, false));
}
