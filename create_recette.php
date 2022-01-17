<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}


if (!isset($_SESSION['RECETTE_CREATE'])) {
    $_SESSION['RECETTE_CREATE'] = true;
    $_SESSION['RECETTE_CREATE_ID'] = 1;
} elseif (!$_SESSION['RECETTE_CREATE']) {
    $_SESSION['RECETTE_CREATE'] = true;
    $_SESSION['RECETTE_CREATE_ID']++;
}

$prefix = 'RECETTE_CREATE_' . $_SESSION['RECETTE_CREATE_ID'] . '_';


?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Recettes</title>
    <link rel="stylesheet" href="css/create_recette.css" />
</head>

<body>
    <div class="main_div">
        <?php $header_searchbar_focus = true; ?>
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1>Création d'une recette</h1>

            <div id="create_recette_main_container">

                <form action="à toi de jouer victor.php" method="POST" id="create_recette_form">

                    <div id="create_recette_information">

                        <h2>Détails de la recette</h2>

                        <h3>Titre de la recette</h3>

                        <input type="text" placeholder="Titre" name="create_recette_title_text" value="<?php if(!isset($_SESSION[$prefix.'title']))?>">

                        <input type="submit" value="Enregistrer" name="create_recette_title_buttom">

                        <h3>Brève description</h3>
                        
                        

                        <input type="submit" value="ajouter une description" name="create_recette_desc_buttom">

                    </div>

<!--                     <div id="create_recette_content">

                        <h2>Contenu de la recette</h2>

                        <div id="create_recette_img">

                            <h3>Image <em>(facultatif)</em>
                                <p><img src="" alt="image de la recette" /></p>
                                <input type="submit" value="ajouter une image" name="create_recette_img_buttom">

                        </div>

                        <div id="create_recette_ingredients">

                            <h3>Ingrédient</h3>

                            <div id="create_recette_ingredients_container">

                                <p></p>
                                <input type="submit" value="" name="create_recette_del_ingredient_buttom">

                            </div>

                            <input type="submit" value="ajouter un ingrédient" name="create_recette_ingredient_buttom">

                        </div>

                        <div id="create_recette_etapes">

                            <h3>Ingrédient</h3>

                            <div id="create_recette_etapes_container">

                                <p></p>
                                <input type="submit" value="" name="create_recette_del_etape_buttom">

                            </div>

                            <input type="submit" value="ajouter une étape" name="create_recette_etape_buttom">

                        </div>

                    </div> -->

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="valider la recette" name="create_recette_valide_recette_buttom">
                        <!-- <input type="submit" value="tout effacer" name="create_recette_del_recette_buttom"> -->
                    </div>

                </form>

            </div>

        </div>

    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>