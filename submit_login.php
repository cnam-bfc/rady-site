<?php
if (
    !isset($_POST['email'])
    || empty($_POST['email'])
    || !isset($_POST['password'])
    || empty($_POST['password'])
) {
    include('includes/error.php');

    return;
}

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Connexion</title>
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div>
            <h1>Vous êtes connecté !</h1>

            <h5>Rappel de vos informations:</h5>
            <p><b>Email</b>: <?php echo $email; ?></p>
            <p><b>Mot de passe</b>: <?php echo $password; ?></p>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>