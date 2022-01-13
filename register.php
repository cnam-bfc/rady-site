<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Inscription</title>
    <link rel="stylesheet" href="css/auth.css" />
</head>

<body>
    <header><?php include('includes/header.php'); ?></header>

    <form method="post" action="submit_register.php">

        <fieldset>
            <legend>Inscription</legend>

            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" autofocus required placeholder="Email" />
            </p>

            <p>
                <label for="pseudo">Pseudo: </label>
                <input type="text" name="pseudo" id="pseudo" required placeholder="Pseudo" />
            </p>

            <p>
                <label for="nom">Nom: </label>
                <input type="text" name="nom" id="nom" required placeholder="Nom" />
            </p>

            <p>
                <label for="prenom">Prénom: </label>
                <input type="text" name="prenom" id="prenom" required placeholder="Prénom" />
            </p>

            <p>
                <label for="password">Mot de passe: </label>
                <input type="password" name="password" id="password" required placeholder="Mot de passe" />
            </p>

            <input type="submit" value="S'inscrire" /></br>
            <a href="login.php">Déjà inscrit?</a>
        </fieldset>
    </form>

    <footer><?php include('includes/footer.php'); ?></footer>
</body>

</html>