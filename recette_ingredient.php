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

$idRecette = htmlspecialchars($_POST['recette']);

$edit = false;
if (isset($_POST['edit']) && !empty($_POST['edit'])) {
    $edit = $_POST['edit'];

    if (
        !isset($_POST['ingredient'])
        || empty($_POST['ingredient'])
    ) {
        $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
        include_once('includes/error.php');
    }

    $idIngredient = htmlspecialchars($_POST['ingredient']);

    try {
        $sqlQuery = 'SELECT * FROM IngredientsRecettes WHERE idRecette = :idRecette AND idIngredient = :idIngredient';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'idRecette' => $idRecette,
            'idIngredient' => $idIngredient
        ]);
        $ingredientsRecettes = $sqlStatement->fetchAll();
        if (count($ingredientsRecettes) == 0) {
            $_SESSION['ERROR_MSG'] = 'Ingrédient introuvable';
            include_once('includes/error.php');
        }

        foreach ($ingredientsRecettes as $ingredientRecette) {
        }
    } catch (Exception $e) {
        $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
        include_once('includes/error.php');
    }
}

try {
    $sqlQuery = 'SELECT * FROM Unites ORDER BY nom ASC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $unites = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

try {
    $sqlQuery = 'SELECT * FROM Ingredients ORDER BY nom ASC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $ingredients = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title><?php if ($edit) echo ("Modifier");
            else echo ("Ajouter"); ?> un ingrédient</title>
    <link rel="stylesheet" href="css/recette_create.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1><?php if ($edit) echo ("Modifier");
                else echo ("Ajouter"); ?> un ingrédient</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_ingredient_<?php if ($edit) echo ("edit");
                                                        else echo ("add"); ?>.php" method="POST" id="create_recette_form">
                    <input type="hidden" name="recette" value="<?php echo ($idRecette); ?>">
                    <?php if ($edit) : ?>
                        <input type="hidden" name="ingredient" value="<?php echo ($idIngredient); ?>">
                    <?php endif; ?>

                    <div id="create_recette_information">

                        <h2>Détails de l'ingrédient</h2>

                        <h3>Ingredient</h3>
                        <select name="ingredient" id="recette_ingredient_unite" required <?php if ($edit) echo ("disabled"); ?>>
                            <!-- Obligatoire pour que l'utilisateur soit obligé de choisir un ingrédient dans la liste -->
                            <option value=""></option>
                            <?php foreach ($ingredients as $ingredient) : ?>
                                <option value="<?php echo ($ingredient['id']); ?>" <?php if ($edit && $ingredientRecette['idIngredient'] == $ingredient['id']) echo ('selected="selected"'); ?>><?php echo ($ingredient['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <h3>Quantité</h3>
                        <div id="recette_ingredient_quantite">
                            <input type="number" min="0" required placeholder="Quantité" name="quantite" <?php if ($edit) echo ('value="' . $ingredientRecette['quantite'] . '"'); ?>>

                            <select name="unite" id="recette_ingredient_unite" required>
                                <?php foreach ($unites as $unite) : ?>
                                    <option value="<?php echo ($unite['nom']); ?>" <?php if ($edit && $ingredientRecette['unite'] == $unite['nom']) echo ('selected="selected"'); ?>><?php echo ($unite['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                    </div>

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="<?php if ($edit) echo ("Modifier");
                                                    else echo ("Ajouter"); ?> l'ingrédient">
                    </div>

                </form>

            </div>


        </div>

    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>