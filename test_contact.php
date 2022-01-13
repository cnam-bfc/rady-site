<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Contact</title>
</head>

<body>
    <header><?php include_once('includes/header.php'); ?></header>

    <div>
        <h1>Contactez nous</h1>
        <form action="test_submit_contact.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="email">Email</label>
                <input type="email" name="email">
            </div>
            <div>
                <label for="message">Votre message</label>
                <textarea placeholder="Exprimez vous" name="message"></textarea>
            </div>
            <div class="mb-3">
                <label for="screenshot">Votre capture d'écran</label>
                <input type="file" id="screenshot" name="screenshot">
            </div>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>