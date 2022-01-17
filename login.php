<?php include_once('includes/init.php'); ?>

<?php
if (isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title>Connexion</title>
    <link rel="stylesheet" href="css/auth.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="auth_main_div">
            <form action="submit_login.php" method="POST" id="auth_form">
                <h1>Connexion</h1>

                <label for="identifiant">Identifiant</label>
                <input type="text" name="identifiant" autofocus required placeholder="Email / Pseudo" />

                <label for="password">Mot de passe</label>
                <input type="password" name="password" required placeholder="Mot de passe" />

                <div id="auth_button_div">
                    <input type="submit" value="Se connecter" id="auth_buttom" /></br>
                    <a href="register.php">Pas encore inscrit?</a>
                </div>
            </form>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>