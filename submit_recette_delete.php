<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);

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

// On supprime la recette en base de données
try {
    $sqlQuery = 'DELETE FROM Recettes WHERE id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $recette
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$_SESSION['REDIRECT_URL'] = './';
include_once('includes/redirect.php');
?>