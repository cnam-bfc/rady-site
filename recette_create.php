<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

$edit = false;
if (isset($_POST['edit']) && !empty($_POST['edit'])) {
    $edit = $_POST['edit'];

    if (
        !isset($_POST['recette'])
        || empty($_POST['recette'])
    ) {
        $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
        include_once('includes/error.php');
    }

    $id = htmlspecialchars($_POST['recette']);

    try {
        $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id AND idAuteur = :idAuteur';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'id' => $id,
            'idAuteur' => $_SESSION['USER_ID']
        ]);
        $recettes = $sqlStatement->fetchAll();
        if (count($recettes) == 0) {
            $_SESSION['ERROR_MSG'] = 'Recette introuvable ou vous n\'etes pas l\'auteur de celle-ci';
            include_once('includes/error.php');
        }

        foreach ($recettes as $recette) {
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
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title><?php if ($edit) echo ("Modification");
            else echo ("Création"); ?> d'une recette</title>
    <link rel="stylesheet" href="css/recette_create.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1><?php if ($edit) echo ("Modification");
                else echo ("Création"); ?> d'une recette</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_<?php if ($edit) echo ("edit");
                                                else echo ("create"); ?>.php" method="POST" id="create_recette_form">
                    <?php if ($edit) : ?>
                        <input type="hidden" name="recette" value="<?php echo ($id); ?>">
                    <?php endif; ?>

                    <div id="create_recette_information">

                        <h2>Détails de la recette</h2>

                        <h3>Titre de la recette</h3>

                        <input type="text" placeholder="Titre" required name="nom" <?php if ($edit) echo ('value="' . $recette['nom'] . '"'); ?>>

                        <h3>Brève description</h3>

                        <textarea rows="4" cols="75" required placeholder="Ecrivez ici une courte description de la recette..." name="description" id="create_recette_desc" maxlength="1024"><?php if ($edit) echo ($recette['description']); ?></textarea>
                    </div>

                    <div id="create_recette_information_supp">

                        <h2>Informations supplémentaires</h2>

                        <h3>Image de la recette (facultatif)</h3>
                        <input type="url" placeholder="URL de l'image" name="imageUrl" <?php if ($edit && isset($recette['imageUrl'])) echo ('value="' . $recette['imageUrl'] . '"'); ?>>

                        <h3>Difficulté de la recette</h3>
                        <select name="difficulte" id="recette_create_difficulte">
                            <option value="Facile" <?php if ($edit && $recette['difficulte'] == "Facile") echo ('selected="selected"'); ?>>Facile</option>
                            <option value="Moyenne" <?php if ($edit && $recette['difficulte'] == "Moyenne") echo ('selected="selected"'); ?>>Moyenne</option>
                            <option value="Difficile" <?php if ($edit && $recette['difficulte'] == "Difficile") echo ('selected="selected"'); ?>>Difficile</option>
                        </select>


                        <h3>Temps de préparation (en minutes)</h3>
                        <input type="number" min="0" placeholder="Temps en minutes" name="tempsPreparation" <?php if ($edit && isset($recette['tempsPreparation'])) echo ('value="' . $recette['tempsPreparation'] . '"'); ?>>

                        <h3>Temps de conservation (en mois)</h3>
                        <input type="number" min="0" placeholder="Temps en mois" name="tempsConservation" <?php if ($edit && isset($recette['tempsConservation'])) echo ('value="' . $recette['tempsConservation'] . '"'); ?>>

                        <h3>Quantité de produit fini</h3>
                        <div id="recette_create_finish_container">
                            <input type="number" min="0" placeholder="Quantité" name="quantite" <?php if ($edit) echo ('value="' . $recette['quantite'] . '"'); ?>>
                            <select name="unite" id="recette_create_difficulte">
                                <?php foreach ($unites as $unite) : ?>
                                    <option value="<?php echo ($unite['nom']); ?>" <?php if ($edit && $recette['unite'] == $unite['nom']) echo ('selected="selected"'); ?>><?php echo ($unite['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="<?php if ($edit) echo ("Modifier");
                                                    else echo ("Créer"); ?> la recette">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>