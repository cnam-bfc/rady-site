<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="css/auth.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include('includes/header.php'); ?></header>

        <div id="auth_main_div">

            <form action="submit_forgot_password.php" method="POST" id="auth_form">

                <h1>Mot de passe oublié</h1>

                <label for="email">Identifiant</label>
                <input type="email" name="email" autofocus required placeholder="Email / Pseudo" />

                <div id="auth_button_div">
                    <input type="submit" value="Valider" id="auth_buttom" /></br>
                </div>

            </form>

        </div>
    </div>

    <footer><?php include('includes/footer.php'); ?></footer>
</body>

</html>