<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
    || !isset($_POST['ingredient'])
    || empty($_POST['ingredient'])
    || !isset($_POST['quantite'])
    || empty($_POST['quantite'])
    || !isset($_POST['unite'])
    || empty($_POST['unite'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);
$ingredient = htmlspecialchars($_POST['ingredient']);
$quantite = htmlspecialchars($_POST['quantite']);
$unite = $_POST['unite'];

// On vérifie que la recette existe et que l'utilisateur est l'auteur de celle-ci
try {
    $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id AND idAuteur = :idAuteur';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $recette,
        'idAuteur' => $_SESSION['USER_ID']
    ]);
    $recettes = $sqlStatement->fetchAll();
    if (count($recettes) == 0) {
        $_SESSION['ERROR_MSG'] = 'Recette introuvable ou vous n\'etes pas l\'auteur de celle-ci';
        include_once('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On ajoute l'ingrédient en base de données
try {
    $sqlQuery = 'INSERT INTO IngredientsRecettes (idRecette, idIngredient, quantite, unite) VALUES (:idRecette, :idIngredient, :quantite, :unite)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idIngredient' => $ingredient,
        'quantite' => $quantite,
        'unite' => $unite
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$_SESSION['REDIRECT_URL'] = 'recette.php?id=' . $recette;
include_once('includes/redirect.php');
?>