<?php include_once('includes/init.php'); ?>

<?php
if (
    isset($_GET['search'])
    && !empty($_GET['search'])
) {
    $search = htmlspecialchars($_GET['search']);
}

try {
    if (!isset($search)) {
        $sqlQuery = 'SELECT * FROM Recettes';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute();
    } else {
        $sqlQuery = 'SELECT * FROM Recettes WHERE nom LIKE :search';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'search' => '%' . $search . '%'
        ]);
    }
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Recettes</title>
    <link rel="stylesheet" href="css/recettes.css" />
</head>

<body>
    <div class="main_div">
        <?php $header_searchbar_focus = true; ?>
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1 id="create_recette_title_page">Création de recette</h1>

            <form action="à toi de jouer victor.php" method="POST" id="create_recette_form">

                <div id="create_recette_information">

                    <h2>Détail de la recette</h2>

                    <label for="Title">Titre de la recette</label>
                    <input type="text" required placeholder="Titre" id="create_recette_title" maxlength="50" />

                    <label for="Description">Brève description</label>
                    <textarea rows="2" cols="75" required placeholder="Description de maximum 150 caractères" id="create_recette_desc" maxlength="150"></textarea>

                </div>

                <div id="create_recette_content">

                    <h2>Contenu de la recette</h2>

                    <div id="create_recette_img">

                        <label for="Image">Image proposé</label>
                        <input type="file" accept="image/*" id="create_recette_img" />

                    </div>

                    <div id="create_recette_ingredients">

                        <h3>Ingrédient</h3>
                        <div id="create_recette_ingredients_checkbox"> 
                            <!-- faudra voir comment tu veux faire mais grossomodo je pense qu'il faudra boucler sur une checkbox  -->
                            <input type="checkbox" class="create_recette_ingredients_checkbox" name="test">
                            <label for="test">test</label>
                            <input type="checkbox" class="create_recette_ingredients_checkbox" name="test2">
                            <label for="test2">test2</label>
                            <input type="checkbox" class="create_recette_ingredients_checkbox" name="test3">
                            <label for="test3">test3</label>
                        </div>

                    </div>

                    <div id="create_recette_etapes">

                    </div>

                </div>

            </form>


        </div>

    </div>

</body>

</html>