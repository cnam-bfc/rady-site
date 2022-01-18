<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}
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

            <h1>Création d'une recette</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_create.php" method="POST" id="create_recette_form">

                    <div id="create_recette_information">

                        <h2>Détails de la recette</h2>

                        <h3>Titre de la recette</h3>

                        <input type="text" placeholder="Titre" required name="title">

                        <!-- en théorie plus besoin du bouton
                        <input type="submit" value="Enregistrer" name="create_recette_title_buttom"> -->

                        <h3>Brève description</h3>

                        <textarea rows="4" cols="75" required placeholder="Ecrivez ici une courte description de la recette..." name="description" id="create_recette_desc" maxlength="255"></textarea>

                    </div>

                    <div id="create_recette_information_supp">

                        <form method="post" action="à toi de jouer gros bg de victor.php">

                            <p>
                                <label for="difficulte">Quelle est la difficulté de la recette ?</label><br />
                                <select nom="difficulte" id="recette_create_difficulte">
                                    <option value="Facile">Facile</option>
                                    <option value="Moyenne">Moyenne</option>
                                    <option value="Difficile">Difficile</option>
                                </select>
                            </p>

                            

                        </form>


                        </p>



                    </div>

                    <div id="create_recette_img">

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
                        <input type="submit" value="Créer la recette">
                        <!-- <input type="submit" value="tout effacer" name="create_recette_del_recette_buttom"> -->
                    </div>

                </form>

            </div>

        </div>

    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>