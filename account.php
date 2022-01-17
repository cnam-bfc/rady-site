<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
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

                <h2>Informations</h2>

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

        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>