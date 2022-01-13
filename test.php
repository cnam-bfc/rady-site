<!-- index.php -->
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Site de recettes - Page d'accueil</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <?php include_once('includes/header.php'); ?>
        <h1>Site de recettes</h1>

        <!-- inclusion des variables et fonctions -->
        <?php
        include_once('test_variables.php');
        include_once('test_functions.php');
        ?>

        <!-- inclusion de l'entête du site -->
        <?php include_once('includes/header.php'); ?>

        <?php foreach (getRecipes($recipes) as $recipe) : ?>
            <article>
                <h3><?php echo $recipe['title']; ?></h3>
                <div><?php echo $recipe['recipe']; ?></div>
                <i><?php echo displayAuthor($recipe['author'], $users); ?></i>
            </article>
        <?php endforeach ?>
    </div>

    <!-- inclusion du bas de page du site -->
    <?php include_once('includes/footer.php'); ?>
</body>

</html>