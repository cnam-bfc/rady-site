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
    || !isset($_POST['utilisateur'])
    || empty($_POST['utilisateur'])
    || !isset($_POST['date'])
    || empty($_POST['date'])
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
$utilisateur = htmlspecialchars($_POST['utilisateur']);
$date = htmlspecialchars($_POST['date']);

// On vérifie que le commentaire existe
try {
    $sqlQuery = 'SELECT * FROM Commentaires WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur AND date = :date';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $utilisateur,
        'date' => $date
    ]);
    $commentaires = $sqlStatement->fetchAll();
    if (count($commentaires) == 0) {
        $_SESSION['ERROR_MSG'] = 'Commentaire introuvable';
        include_once('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On supprime si il existe déjà un like en base de données
try {
    $sqlQuery = 'DELETE FROM LikesCommentaires WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur AND dateCommentaire = :dateCommentaire AND idAuteurCommentaire = :idAuteurCommentaire';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $_SESSION['USER_ID'],
        'dateCommentaire' => $date,
        'idAuteurCommentaire' => $utilisateur
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On ajoute le like en base de données
try {
    $sqlQuery = 'INSERT INTO LikesCommentaires (idRecette, idUtilisateur, dateCommentaire, idAuteurCommentaire, aime) VALUES (:idRecette, :idUtilisateur, :dateCommentaire, :idAuteurCommentaire, :aime)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $_SESSION['USER_ID'],
        'dateCommentaire' => $date,
        'idAuteurCommentaire' => $utilisateur,
        'aime' => $aime
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

include_once('includes/redirect_backward.php');
?>