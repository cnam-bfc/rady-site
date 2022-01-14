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
        $sqlQuery = 'SELECT * FROM Recettes';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute();
    } else {
        $sqlQuery = 'SELECT * FROM Recettes WHERE nom LIKE :search';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'search' => '%' . $search . '%'
        ]);
    }
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>

    <title>Recettes</title>
    <link rel="stylesheet" href="css/recettes.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="recettes_main">

            <?php if (!isset($search)) : ?>
                <h1>Liste des recettes</h1>
            <?php else : ?>
                <h1>Résultats de la recherche de "<?php echo $search; ?>"</h1>
            <?php endif; ?>

            <?php foreach ($recettes as $recette) : ?>
                <div class="recettes_container">
                    <div id="recettes_nom">
                        <p><?php echo $recette['nom']; ?></p>
                    </div>

                    <div id="recettes_desc">
                        <p><?php echo $recette['description']; ?></p>
                    </div>

                    <div id="recettes_note">
                        <p>NOTE</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>