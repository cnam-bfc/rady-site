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
        !isset($_POST['ustensile'])
        || empty($_POST['ustensile'])
    ) {
        $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
        include_once('includes/error.php');
    }

    $idUstensile = htmlspecialchars($_POST['ustensile']);

    try {
        $sqlQuery = 'SELECT * FROM UstensilesRecettes WHERE idRecette = :idRecette AND idUstensile = :idUstensile';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'idRecette' => $idRecette,
            'idUstensile' => $idUstensile
        ]);
        $ustensilesRecettes = $sqlStatement->fetchAll();
        if (count($ustensilesRecettes) == 0) {
            $_SESSION['ERROR_MSG'] = 'Ustensile introuvable';
            include_once('includes/error.php');
        }

        foreach ($ustensilesRecettes as $ustensileRecette) {
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
    $sqlQuery = 'SELECT * FROM Ustensiles ORDER BY nom ASC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $ustensiles = $sqlStatement->fetchAll();
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
            else echo ("Ajouter"); ?> un ustensile</title>
    <link rel="stylesheet" href="css/recette_create.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1><?php if ($edit) echo ("Modifier");
                else echo ("Ajouter"); ?> un ustensile</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_ustensile_<?php if ($edit) echo ("edit");
                                                        else echo ("add"); ?>.php" method="POST" id="create_recette_form">
                    <input type="hidden" name="recette" value="<?php echo ($idRecette); ?>">
                    <?php if ($edit) : ?>
                        <input type="hidden" name="ustensile" value="<?php echo ($idUstensile); ?>">
                    <?php endif; ?>

                    <div id="create_recette_information">

                        <h2>Détails de l'ustensile</h2>

                        <h3>Ustensile</h3>
                        <select name="ustensile" id="recette_ingredient_unite" required <?php if ($edit) echo ("disabled"); ?>>
                            <!-- Obligatoire pour que l'utilisateur soit obligé de choisir un ustensile dans la liste -->
                            <option value=""></option>
                            <?php foreach ($ustensiles as $ustensile) : ?>
                                <option value="<?php echo ($ustensile['id']); ?>" <?php if ($edit && $ustensileRecette['idUstensile'] == $ustensile['id']) echo ('selected="selected"'); ?>><?php echo ($ustensile['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <h3>Quantité <em>(unité facultatif)</em></h3>
                        <div id="recette_ingredient_quantite">
                            <input type="number" min="0" required placeholder="Quantité" name="quantite" <?php if ($edit) echo ('value="' . $ustensileRecette['quantite'] . '"'); ?>>

                            <select name="unite" id="recette_ingredient_unite" required>
                                <?php foreach ($unites as $unite) : ?>
                                    <option value="<?php echo ($unite['nom']); ?>" <?php if ($edit && $ustensileRecette['unite'] == $unite['nom']) echo ('selected="selected"'); ?>><?php if ($unite['nom'] == ' ') echo ('Aucune unité');
                                                                                                                                                                                    else echo ($unite['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                    </div>

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="<?php if ($edit) echo ("Modifier");
                                                    else echo ("Ajouter"); ?> l'ustensile">
                    </div>

                </form>

            </div>


        </div>

    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>