<?php
if (
    !isset($_POST['email'])
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    || !isset($_POST['pseudo'])
    || empty($_POST['pseudo'])
    || !isset($_POST['nom'])
    || empty($_POST['nom'])
    || !isset($_POST['prenom'])
    || empty($_POST['prenom'])
    || !isset($_POST['password'])
    || empty($_POST['password'])
) {
    include('includes/error.php');

    return;
}

$email = htmlspecialchars($_POST['email']);
$pseudo = htmlspecialchars($_POST['pseudo']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$password = htmlspecialchars($_POST['password']);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Inscription</title>
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div>
            <h1>Vous êtes inscrit !</h1>

            <h5>Rappel de vos informations:</h5>
            <p><b>Email</b>: <?php echo $email; ?></p>
            <p><b>Pseudo</b>: <?php echo $pseudo; ?></p>
            <p><b>Nom</b>: <?php echo $nom; ?></p>
            <p><b>Prénom</b>: <?php echo $prenom; ?></p>
            <p><b>Mot de passe</b>: <?php echo $password; ?></p>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>