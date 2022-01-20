<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

try {
    $sqlQuery = 'SELECT * FROM RecettesLikes WHERE idAuteur = :idAuteur ORDER BY aime DESC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idAuteur' => $_SESSION['USER_ID']
    ]);
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title>Mon compte</title>
    <link rel="stylesheet" href="css/account.css" />
</head>

<body>
    <div class="main_div">

        <header><?php include_once('includes/header.php'); ?></header>

        <div id="account_main_div">

            <h1>Bienvenue sur l'espace Mon compte, <?php echo $_SESSION['USER_PRENOM'] . ' ' . $_SESSION['USER_NOM']; ?></h1>

            <div id="account_container">

                <h2>Mes informations</h2>

                <div id="account_info">
                    <div id="account_info2">
                        <p><strong>Pseudo :</strong></p>
                        <p><strong>Email :</strong></p>
                        <p><strong>Nom :</strong></p>
                        <p><strong>Prénom :</strong></p>
                    </div>

                    <div id="account_info3">

                        <P><?php echo ($_SESSION['USER_PSEUDO']); ?></p>
                        <P><?php echo ($_SESSION['USER_EMAIL']); ?></p>
                        <P><?php echo ($_SESSION['USER_NOM']); ?></p>
                        <P><?php echo ($_SESSION['USER_PRENOM']); ?></p>

                    </div>
                </div>

                <a href="submit_account_delete.php" id="account_delete">Supprimer mon compte</a>

            </div>

            <div id="account_recette">

                <h2>Mes recettes</h2>

                <?php if (count($recettes) == 0) : ?>
                    <p>Vous n'avez créer aucune recette</p>
                <?php endif; ?>

                <h3 id="account_recette_create"><a href="recette_create.php" />Créer une recette</h3>

                <?php foreach ($recettes as $recette) : ?>
                    <a href="recette.php?id=<?php echo ($recette['id']); ?>" id="account_recette_link">
                        <div id="account_recette_container_1">

                            <h3><?php echo ($recette['nom']); ?></h3>

                            <p><?php echo ($recette['description']); ?></p>

                            <div id="account_recette_container_2">

                                <img src="img/eye<?php if (!$recette['visible']) echo ("_selected"); ?>.png" alt="visibilité de la recette" />

                                <em><?php echo ($recette['aime'] . ' Like'); ?></em>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>