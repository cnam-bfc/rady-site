<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
    || !isset($_POST['description'])
    || empty($_POST['description'])
    || !isset($_POST['idAnimation'])
    || empty($_POST['idAnimation'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);
$description = $_POST['description'];
$idAnimation = htmlspecialchars($_POST['idAnimation']);

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

// On recupère l'étape avec le plus grand id pour une recette
try {
    $sqlQuery = 'SELECT MAX(id) as max FROM Etapes WHERE idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette
    ]);
    $etapes = $sqlStatement->fetchAll();
    $maxEtape = 0;
    foreach ($etapes as $etape) {
        if ($maxEtape < $etape['max']) {
            $maxEtape = $etape['max'];
        }
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$maxEtape++;
// On ajoute l'étape en base de données
try {
    $sqlQuery = 'INSERT INTO Etapes (idRecette, id, description, idAnimation) VALUES (:idRecette, :id, :description, :idAnimation)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'id' => $maxEtape,
        'description' => $description,
        'idAnimation' => $idAnimation
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$_SESSION['REDIRECT_URL'] = 'recette.php?id=' . $recette;
include_once('includes/redirect.php');
?>