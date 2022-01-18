<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['recette'])
    || empty($_POST['recette'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$recette = htmlspecialchars($_POST['recette']);


?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title>Recettes</title>
    <link rel="stylesheet" href="css/recette_create.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>
        
        <div id="create_recette_main">

            <h1>Création une ingrédient</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_create.php" method="POST" id="create_recette_form">

                    <div id="create_recette_information">

                        <h2>Détails de la recette</h2>

                        <h3>Titre de la recette</h3>

                        <input type="text" placeholder="Titre" required name="title">

                        <h3>Brève description</h3>

                        <textarea rows="4" cols="75" required placeholder="Ecrivez ici une courte description de la recette..." 
                        name="description" id="create_recette_desc" maxlength="255"></textarea>

                    </div>

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="Créer la recette">
                    </div>

                </form>

            </div>

        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>