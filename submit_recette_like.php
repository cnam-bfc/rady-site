<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['aime'])
    || empty($_POST['aime'])
    || !isset($_POST['recette'])
    || empty($_POST['recette'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$aime = htmlspecialchars($_POST['aime']);
// aime parser
if ($aime == "true") {
    $aime = 1;
} elseif ($aime == "false") {
    $aime = 0;
} else {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}
$recette = htmlspecialchars($_POST['recette']);

// On vérifie que la recette existe
try {
    $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $recette
    ]);
    $recettes = $sqlStatement->fetchAll();
    if (count($recettes) == 0) {
        $_SESSION['ERROR_MSG'] = 'Recette introuvable';
        include('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On supprime si il existe déjà un like en base de données
try {
    $sqlQuery = 'DELETE FROM LikesRecettes WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $_SESSION['USER_ID']
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On ajoute le like en base de données
try {
    $sqlQuery = 'INSERT INTO LikesRecettes (idRecette, idUtilisateur, aime) VALUES (:idRecette, :idUtilisateur, :aime)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $_SESSION['USER_ID'],
        'aime' => $aime
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

include_once('includes/redirect_backward.php');
?>