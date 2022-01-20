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
    $sqlQuery = 'SELECT UstensilesRecettes.idUstensile as id, Ustensiles.nom, Ustensiles.imageUrl, UstensilesRecettes.quantite, UstensilesRecettes.unite
    FROM UstensilesRecettes, Ustensiles
    WHERE UstensilesRecettes.idUstensile = Ustensiles.id AND UstensilesRecettes.idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $idRecette
    ]);
    $ustensiles = $sqlStatement->fetchAll();
    if (count($ustensiles) == 0) {
        echo ("ERREUR: Ustensiles de la recette avec l'id " . $idRecette . " introuvable");
        die();
    }
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

$nb = count($ustensiles);
foreach ($ustensiles as $ustensile) {
    $nb--;
    echo ('ustensile_' . $ustensile['id'] . $separator);
    echo (displayKeyValue($ustensile, 'id', $separator, true));
    echo (displayKeyValue($ustensile, 'nom', $separator, true));
    echo (displayKeyValue($ustensile, 'imageUrl', $separator, true));
    echo (displayKeyValue($ustensile, 'quantite', $separator, true));
    if ($nb != 0)
        echo (displayKeyValue($ustensile, 'unite', $separator, true));
    else
        echo (displayKeyValue($ustensile, 'unite', $separator, false));
}
