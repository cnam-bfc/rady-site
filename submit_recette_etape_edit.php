<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
    || !isset($_POST['id'])
    || empty($_POST['id'])
    || !isset($_POST['new_id'])
    || empty($_POST['new_id'])
    || !isset($_POST['new_description'])
    || empty($_POST['new_description'])
    || !isset($_POST['new_id_animation'])
    || empty($_POST['new_id_animation'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);
$id = htmlspecialchars($_POST['id']);
$new_id = htmlspecialchars($_POST['new_id']);
$new_description = htmlspecialchars($_POST['new_description']);
$new_id_animation = htmlspecialchars($_POST['new_id_animation']);

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

// On modifie l'étape en base de données
try {
    $sqlQuery = 'UPDATE Etapes SET id = :new_id, description = :new_description, idAnimation = :new_id_animation WHERE idRecette = :idRecette AND id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette,
        'id' => $id,
        'new_id' => $new_id,
        'new_description' => $new_description,
        'new_id_animation' => $new_id_animation
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$_SESSION['REDIRECT_URL'] = 'recette.php?id=' . $recette;
include_once('includes/redirect.php');
?>