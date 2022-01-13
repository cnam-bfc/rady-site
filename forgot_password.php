<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Mot de passe oublié</title>
</head>

<body>
    <header><?php include('includes/header.php'); ?></header>

    <form action="submit_forgot_password.php" method="POST">
        <fieldset>
            <legend>Mot de passe oublié</legend>

            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" autofocus required placeholder="Email" />
            </p>

            <input type="submit" value="Valider" /></br>
        </fieldset>
    </form>

    <footer><?php include('includes/footer.php'); ?></footer>
</body>

</html>