<?php include_once('includes/init.php'); ?>

<?php
if (
    !isset($_POST['email'])
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    || !isset($_POST['message'])
    || empty($_POST['message'])
) {
    $_SESSION['ERROR_MSG'] = 'Données transmises incorectes !';
    include_once('includes/error.php');
}

$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Submit</title>
</head>

<body>
    <header><?php include_once('includes/header.php'); ?></header>

    <div>
        <h1>Message bien reçu !</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Rappel de vos informations</h5>
                <p class="card-text"><b>Email</b> : <?php echo $email; ?></p>
                <p class="card-text"><b>Message</b> : <?php echo $message; ?></p>
            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>