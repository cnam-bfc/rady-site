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
        !isset($_POST['etape'])
        || empty($_POST['etape'])
    ) {
        $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
        include_once('includes/error.php');
    }

    $idEtape = htmlspecialchars($_POST['etape']);

    try {
        $sqlQuery = 'SELECT * FROM Etapes WHERE idRecette = :idRecette AND id = :idEtape';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'idRecette' => $idRecette,
            'idEtape' => $idEtape
        ]);
        $etapes = $sqlStatement->fetchAll();
        if (count($etapes) == 0) {
            $_SESSION['ERROR_MSG'] = 'Etape introuvable';
            include_once('includes/error.php');
        }

        foreach ($etapes as $etape) {
        }
    } catch (Exception $e) {
        $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
        include_once('includes/error.php');
    }
} else {
    // On recupère l'étape avec le plus grand id pour une recette
    try {
        $sqlQuery = 'SELECT MAX(id) as max FROM Etapes WHERE idRecette = :idRecette';
        $sqlStatement = $mysqlClient->prepare($sqlQuery);
        $sqlStatement->execute([
            'idRecette' => $idRecette
        ]);
        $etapes = $sqlStatement->fetchAll();
        $maxEtape = 0;
        foreach ($etapes as $etape) {
            if ($maxEtape < $etape['max']) {
                $maxEtape = $etape['max'];
            }
        }
    } catch (Exception $e) {
        $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
        include_once('includes/error.php');
    }

    $maxEtape++;
}

try {
    $sqlQuery = 'SELECT * FROM Animations ORDER BY nom ASC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $animations = $sqlStatement->fetchAll();
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
            else echo ("Ajouter"); ?> une étape</title>
    <link rel="stylesheet" href="css/recette_create.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="create_recette_main">

            <h1><?php if ($edit) echo ("Modifier");
                else echo ("Ajouter"); ?> une étape</h1>

            <div id="create_recette_main_container">

                <form action="submit_recette_etape_<?php if ($edit) echo ("edit");
                                                    else echo ("add"); ?>.php" method="POST" id="create_recette_form">
                    <input type="hidden" name="recette" value="<?php echo ($idRecette); ?>">
                    <?php if ($edit) : ?>
                        <input type="hidden" name="id" value="<?php echo ($idEtape); ?>">
                    <?php endif; ?>

                    <div id="create_recette_information">

                        <h2>Détails de l'étape</h2>

                        <h3>Numéro de l'étape</h3>
                        <input type="number" min="1" required placeholder="ID" name="new_id" <?php if ($edit) echo ('value="' . $etape['id'] . '"');
                                                                                                else echo ('value="' . $maxEtape . '"'); ?>>

                        <h3>Description de l'étape</h3>
                        <textarea rows="4" cols="75" required placeholder="Ecrivez ici l'étape'..." name="new_description" id="recette_etape_desc" maxlength="1024"><?php if ($edit) echo ($etape['description']); ?></textarea>

                        <h3>Animation de l'étape</h3>
                        <select name="new_id_animation" id="recette_etape_anim">
                            <?php foreach ($animations as $animation) : ?>
                                <option value="<?php echo ($animation['id']); ?>" <?php if ($edit && $etape['idAnimation'] == $animation['id']) echo ('selected="selected"'); ?>><?php echo ($animation['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div id="create_recette_final_buttom">
                        <input type="submit" value="<?php if ($edit) echo ("Modifier");
                                                    else echo ("Ajouter"); ?> l'étape">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>