<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Mon compte</title>
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <h1>Bienvenue sur l'espace Mon compte, <?php echo $_SESSION['USER_PRENOM'] . ' ' . $_SESSION['USER_NOM']; ?></h1>

        <p><strong>Pseudo</strong>: <?php echo ($_SESSION['USER_PSEUDO']); ?></p>
        <p><strong>Email</strong>: <?php echo ($_SESSION['USER_EMAIL']); ?></p>
        <p><strong>Nom</strong>: <?php echo ($_SESSION['USER_NOM']); ?></p>
        <p><strong>Prénom</strong>: <?php echo ($_SESSION['USER_PRENOM']); ?></p>
        <a href="submit_account_deletion.php">Supprimer mon compte</a>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>