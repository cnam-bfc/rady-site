<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['title'])
    || empty($_POST['title'])
    || !isset($_POST['description'])
    || empty($_POST['description'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$title = htmlspecialchars($_POST['title']);
$description = htmlspecialchars($_POST['description']);

// On ajoute la recette en base de données
try {
    $sqlQuery = 'INSERT INTO Recettes (nom, description, idAuteur) VALUES (:nom, :description, :idAuteur) RETURNING id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'nom' => $title,
        'idAuteur' => $_SESSION['USER_ID'],
        'description' => $description
    ]);
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

foreach ($recettes as $recette) {
}

$_SESSION['REDIRECT_URL'] = 'recette.php?id=' . $recette['id'];
include_once('includes/redirect.php');
?>