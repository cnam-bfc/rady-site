<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
    || !isset($_POST['utilisateur'])
    || empty($_POST['utilisateur'])
    || !isset($_POST['date'])
    || empty($_POST['date'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);
$utilisateur = htmlspecialchars($_POST['utilisateur']);
$date = htmlspecialchars($_POST['date']);

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
    include('includes/error.php');
}

include_once('includes/redirect_backward.php');
?>