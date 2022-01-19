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

$id = $_POST['recette'];

// On vérifie que la recette existe et que l'utilisateur est l'auteur de celle-ci
try {
    $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id AND idAuteur = :idAuteur';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $id,
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

// La recette est dans le tableau $recette
foreach ($recettes as $recette) {
}

function updateRecette(PDO $mysqlClient, string $recette, array $old, array $new, string $key)
{
    if (isset($new[$key]) && $old[$key] != $new[$key]) {
        try {
            $sqlQuery = 'UPDATE Recettes SET ' . $key . ' = :value WHERE id = :id';
            $sqlStatement = $mysqlClient->prepare($sqlQuery);
            $sqlStatement->execute([
                'value' => $new[$key],
                'id' => $recette
            ]);
        } catch (Exception $e) {
            $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
            include_once('includes/error.php');
        }
    }
}

updateRecette($mysqlClient, $id, $recette, $_POST, 'nom');
updateRecette($mysqlClient, $id, $recette, $_POST, 'description');
updateRecette($mysqlClient, $id, $recette, $_POST, 'imageUrl');
updateRecette($mysqlClient, $id, $recette, $_POST, 'quantite');
updateRecette($mysqlClient, $id, $recette, $_POST, 'difficulte');
updateRecette($mysqlClient, $id, $recette, $_POST, 'tempsPreparation');
updateRecette($mysqlClient, $id, $recette, $_POST, 'tempsConservation');
updateRecette($mysqlClient, $id, $recette, $_POST, 'unite');

$_SESSION['REDIRECT_URL'] = 'recette.php?id=' . $recette['id'];
include_once('includes/redirect.php');
?>