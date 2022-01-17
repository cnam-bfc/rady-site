<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['commentaire'])
    || empty($_POST['commentaire'])
    || !isset($_POST['recette'])
    || empty($_POST['recette'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$commentaire = htmlspecialchars($_POST['commentaire']);
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

// On ajoute le commentaire en base de données
try {
    $sqlQuery = 'INSERT INTO Commentaires (idRecette, idUtilisateur, commentaire) VALUES (:idRecette, :idUtilisateur, :commentaire)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'idUtilisateur' => $_SESSION['USER_ID'],
        'commentaire' => $commentaire
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

$_SESSION['REFRESH_PAGE'] = 2;
include_once('includes/redirect_backward.php');
?>