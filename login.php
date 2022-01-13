<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Connexion</title>
    <link rel="stylesheet" href="css/auth.css" />
</head>


<header><?php include('includes/header.php'); ?></header>

<body>

    <div id="auth_main_div">

        <form action="submit_login.php" method="POST" id="auth_form">

            <h1>Connexion</h1></br>

            <label for="email">Identifiant</label>
            <input type="email" name="email" autofocus required placeholder="Email / pseudo" /></br>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" required placeholder="Mot de passe" />

            <div id="auth_button_div">
                <input type="submit" value="Se connecter" id="auth_buttom"/></br>
                <a href="forgot_password.php">Mot de passe oublié?</a></br>
                <a href="register.php">Pas encore inscrit?</a>
            </div>

        </form>

    </div>

    <footer><?php include('includes/footer.php'); ?></footer>
</body>

</html>