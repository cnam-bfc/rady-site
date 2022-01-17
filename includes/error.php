<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title>Erreur</title>
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div>
            <h1>Une erreur est survenue</h1>

            <?php if (isset($_SESSION['ERROR_MSG']) && !empty($_SESSION['ERROR_MSG'])) : ?>
                <strong><?php echo $_SESSION['ERROR_MSG']; ?></strong></br>
            <?php endif; ?>

            <a href="./">Retour à l'accueil</a>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>
<?php die(); ?>