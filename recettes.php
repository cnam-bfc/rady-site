<?php include_once('includes/init.php'); ?>

<?php
if (
    isset($_GET['search'])
    && !empty($_GET['search'])
) {
    $search = htmlspecialchars($_GET['search']);
}

try {
    if (!isset($search)) {
        $sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute();
    } else {
        $sqlQuery = 'SELECT * FROM Recettes WHERE visible = 1 AND nom LIKE :search';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'search' => '%' . $search . '%'
        ]);
    }
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
    <title>Recettes</title>
    <link rel="stylesheet" href="css/recettes.css" />
</head>

<body>
    <div class="main_div">
        <?php $header_searchbar_focus = true; ?>
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="recettes_main">

            <?php if (count($recettes) == 0) : ?>
                <h1>Aucun résultat pour "<?php echo $search; ?>"</h1>
            <?php else : ?>
                <?php if (!isset($search)) : ?>
                    <h1>Liste des recettes</h1>
                <?php else : ?>
                    <h1>Résultats de la recherche "<?php echo $search; ?>"</h1>
                <?php endif; ?>

                <?php if (isset($_SESSION['USER_LOGGED'])) : ?>
                    <div id="recettes_create">
                        <p><a href="recette_create.php" />Créer une recette</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($recettes as $recette) : ?>
                    <a href=<?php echo ('recette.php?id=' . htmlspecialchars($recette['id'])); ?>>
                        <div class="recettes_container">
                            <div class="recettes_nom">
                                <p><?php echo htmlspecialchars($recette['nom']); ?></p>
                            </div>

                            <div class="recettes_desc">
                                <p><?php echo htmlspecialchars($recette['description']); ?></p>
                            </div>

                            <div class="recettes_note">
                                <?php
                                // On récupère le nombre de like de la recette en bdd
                                try {
                                    $sqlQuery = 'SELECT aime FROM LikesRecettes WHERE idRecette = :idRecette';
                                    $sqlStatement = $mysqlClient->prepare($sqlQuery);
                                    $sqlStatement->execute([
                                        'idRecette' => $recette['id']
                                    ]);
                                    $likes = $sqlStatement->fetchAll();

                                    $nbLike = 0;
                                    $nbDislike = 0;
                                    foreach ($likes as $like) {
                                        if ($like['aime'] == 1) {
                                            $nbLike++;
                                        } else {
                                            $nbDislike++;
                                        }
                                    }
                                } catch (Exception $e) {
                                    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                                    include_once('includes/error.php');
                                }
                                ?>
                                <p><?php echo (($nbLike - $nbDislike) . ' Like'); ?></p>
                            </div>
                        </div>
                    </a>
                    <div class="recettes_separator">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>