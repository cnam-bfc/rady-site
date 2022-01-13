<?php
if (
    !isset($_POST['email'])
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
    include('includes/error.php');

    return;
}

$email = htmlspecialchars($_POST['email']);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Mot de passe oublié</title>
</head>

<body>
    <header><?php include_once('includes/header.php'); ?></header>

    <div>
        <h1>Un email de récupération vous a été envoyé</h1>

        <h5>Rappel de vos informations:</h5>
        <p><b>Email</b>: <?php echo $email; ?></p>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>