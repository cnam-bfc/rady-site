<?php include_once('includes/init.php'); ?>

<?php
// On vérifie que l'id est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    include_once('includes/redirect_backward.php');
}

$id = htmlspecialchars($_GET['id']);

// On vérifie que l'id de la recette existe dans la bdd
try {
    $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $id
    ]);
    $recettes = $sqlStatement->fetchAll();
    if (count($recettes) == 0) {
        $_SESSION['ERROR_MSG'] = 'Recette introuvable';
        include('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// La recette est dans le tableau $recette
foreach ($recettes as $recette) {
}

if ($recette['visible'] == 0) {
    $_SESSION['ERROR_MSG'] = 'Recette en préparation';
    include('includes/error.php');
}

// On récupère les commentaires de la recette
try {
    $sqlQuery = 'SELECT * FROM Commentaires WHERE idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
    $commentaires = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On récupère les étapes de la recette
try {
    $sqlQuery = 'SELECT * FROM Etapes WHERE idRecette = :idRecette';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
    $etapes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On récupère les ingrédients de la recette
try {
    $sqlQuery = 'SELECT DISTINCT Ingredients.nom FROM Etapes, Ingredients, IngredientsEtapes WHERE Etapes.idRecette = :idRecette AND Etapes.idRecette = IngredientsEtapes.idRecette AND IngredientsEtapes.idIngredient = Ingredients.id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
    $ingredients = $sqlStatement->fetchAll();
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
    <link rel="stylesheet" href="css/recette.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="recette_main">
            <!-- Nom de la recette -->
            <div id="recette_title">
                <h1><?php echo htmlspecialchars($recette['nom']); ?></h1>
            </div>

            <!-- Description de la recette -->
            <div id="recette_desc">
                <h3><?php echo htmlspecialchars($recette['description']); ?></h3>
            </div>

            <!-- Likes de la recette -->
            <div id="recette_note_like">
                <!-- Notes / Likes de la recette -->
                <div id="recette_note_avg">
                    <?php
                    // On récupère le nombre de like de la recette en bdd
                    try {
                        $sqlQuery = 'SELECT aime FROM LikesRecettes WHERE idRecette = :idRecette';
                        $sqlStatement = $mysqlClient->prepare($sqlQuery);
                        $sqlStatement->execute([
                            'idRecette' => $recette['id']
                        ]);
                        $likes = $sqlStatement->fetchAll();

                        $nbLike = 0;
                        $nbDislike = 0;
                        foreach ($likes as $like) {
                            if ($like['aime'] == 1) {
                                $nbLike++;
                            } else {
                                $nbDislike++;
                            }
                        }
                    } catch (Exception $e) {
                        $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                        include('includes/error.php');
                    }
                    ?>
                    <p id="recette_avg"><?php echo (($nbLike - $nbDislike) . ' Like'); ?></p>
                </div>

                <?php if (isset($_SESSION['USER_LOGGED'])) : ?>
                    <!-- Les boutons like et dislike -->
                    <div id="recette_like_dislike">
                        <?php
                        // On check si l'utilisateur a aimé la recette ou pas
                        try {
                            $sqlQuery = 'SELECT * FROM LikesRecettes WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur';
                            $sqlStatement = $mysqlClient->prepare($sqlQuery);
                            $sqlStatement->execute([
                                'idRecette' => $recette['id'],
                                'idUtilisateur' => $_SESSION['USER_ID']
                            ]);
                            $likes = $sqlStatement->fetchAll();

                            foreach ($likes as $like) {
                            }
                        } catch (Exception $e) {
                            $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                            include('includes/error.php');
                        }
                        ?>

                        <!-- Bouton like -->
                        <form action="submit_recette_like<?php if (count($likes) > 0) echo ("_deletion"); ?>.php" method="POST" id="recette_like">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="hidden" name="aime" value="true" />
                            <input type="image" id="like_buttom" alt="like" src="img/like<?php if (count($likes) > 0 && $like['aime']) echo ("_selected"); ?>.png" /></br>
                        </form>

                        <!-- Bouton dislike -->
                        <form action="submit_recette_like<?php if (count($likes) > 0) echo ("_deletion"); ?>.php" method="POST" id="recette_dislike">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="hidden" name="aime" value="false" />
                            <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike<?php if (count($likes) > 0 && !$like['aime']) echo ("_selected"); ?>.png" /></br>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($recette['imageUrl'] != null) : ?>
                <!-- Image de la recette -->
                <div id="recette_img">
                    <p><img src="<?php echo ($recette['imageUrl']); ?>" alt="image de la recette" /></p>
                </div>
            <?php endif; ?>

            <!-- Ingrédents de la recette -->
            <div id="recette_ingrediants">
                <h2>Ingrédients</h2>

                <ul>
                    <?php foreach ($ingredients as $ingredient) : ?>
                        <li><?php echo ($ingredient['nom']); ?></li>
                    <?php endforeach; ?>
                </ul>

            </div>

            <!-- Étapes de la recette -->
            <div id="recette_etapes">
                <h2>Étapes</h2>
                <ol>
                    <?php foreach ($etapes as $etape) : ?>
                        <li><?php echo ($etape['description']); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <!-- Auteur de la recette -->
            <?php if ($recette['idAuteur'] != null) : ?>
                <?php
                try {
                    $sqlQuery = 'SELECT * FROM Utilisateurs WHERE id = :id';
                    $sqlStatement = $mysqlClient->prepare($sqlQuery);
                    $sqlStatement->execute([
                        'id' => $recette['idAuteur']
                    ]);
                    $utilisateurs = $sqlStatement->fetchAll();
                } catch (Exception $e) {
                    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                    include('includes/error.php');
                }

                foreach ($utilisateurs as $utilisateur) {
                }
                ?>
                <div id="recette_author">
                    <p><?php echo 'Auteur: ' . htmlspecialchars($utilisateur['pseudo']); ?></p>
                </div>
            <?php endif; ?>

            <!-- Commentaires -->
            <h1 id="recette_comment_title">Commentaires</h1>
            <div id="recette_container_comment_main">
                <?php foreach ($commentaires as $commentaire) : ?>
                    <?php
                    // On récupère l'auteur du commentaire
                    try {
                        $sqlQuery = 'SELECT * FROM Utilisateurs WHERE id = :id';
                        $sqlStatement = $mysqlClient->prepare($sqlQuery);
                        $sqlStatement->execute([
                            'id' => $commentaire['idUtilisateur']
                        ]);
                        $auteursCommentaire = $sqlStatement->fetchAll();
                    } catch (Exception $e) {
                        $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                        include('includes/error.php');
                    }

                    foreach ($auteursCommentaire as $auteurCommentaire) {
                    }
                    ?>

                    <!-- Commentaire -->
                    <div id="recette_container_comment">
                        <!-- Entête du commentaire -->
                        <div id="recette_container_name_like">
                            <!-- Pseudo de l'auteur du commentaire -->
                            <em><?php echo htmlspecialchars($auteurCommentaire['pseudo']); ?></em>

                            <!-- Likes du commentaire -->
                            <?php
                            // On récupère le nombre de like du commentaire en bdd
                            try {
                                $sqlQuery = 'SELECT aime FROM LikesCommentaires WHERE idRecette = :idRecette 
                                    AND dateCommentaire = :dateCommentaire AND idAuteurCommentaire = :idAuteurCommentaire';
                                $sqlStatement = $mysqlClient->prepare($sqlQuery);
                                $sqlStatement->execute([
                                    'idRecette' => $commentaire['idRecette'],
                                    'dateCommentaire' => $commentaire['date'],
                                    'idAuteurCommentaire' => $commentaire['idUtilisateur']
                                ]);
                                $likes = $sqlStatement->fetchAll();

                                $nbLike = 0;
                                $nbDislike = 0;
                                foreach ($likes as $like) {
                                    if ($like['aime'] == 1) {
                                        $nbLike++;
                                    } else {
                                        $nbDislike++;
                                    }
                                }
                            } catch (Exception $e) {
                                $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                                include('includes/error.php');
                            }
                            ?>
                            <p><?php echo (($nbLike - $nbDislike) . ' Like'); ?></p>

                            <?php if (isset($_SESSION['USER_LOGGED'])) : ?>
                                <?php
                                // On check si l'utilisateur a aimé le commentaire ou pas
                                try {
                                    $sqlQuery = 'SELECT * FROM LikesCommentaires WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur 
                                    AND dateCommentaire = :dateCommentaire AND idAuteurCommentaire = :idAuteurCommentaire';
                                    $sqlStatement = $mysqlClient->prepare($sqlQuery);
                                    $sqlStatement->execute([
                                        'idRecette' => $commentaire['idRecette'],
                                        'idUtilisateur' => $_SESSION['USER_ID'],
                                        'dateCommentaire' => $commentaire['date'],
                                        'idAuteurCommentaire' => $commentaire['idUtilisateur']
                                    ]);
                                    $likes = $sqlStatement->fetchAll();

                                    foreach ($likes as $like) {
                                    }
                                } catch (Exception $e) {
                                    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                                    include('includes/error.php');
                                }
                                ?>

                                <!-- Bouton liker commentaire -->
                                <form action="submit_recette_comment_like<?php if (count($likes) > 0) echo ("_deletion"); ?>.php" method="POST" id="recette_like">
                                    <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                    <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                    <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                    <input type="hidden" name="aime" value="true" />
                                    <input type="image" id="like_buttom" alt="like" src="img/like<?php if (count($likes) > 0 && $like['aime']) echo ("_selected"); ?>.png" /></br>
                                </form>

                                <!-- Bouton disliker commentaire -->
                                <form action="submit_recette_comment_like<?php if (count($likes) > 0) echo ("_deletion"); ?>.php" method="POST" id="recette_dislike">
                                    <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                    <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                    <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                    <input type="hidden" name="aime" value="false" />
                                    <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike<?php if (count($likes) > 0 && !$like['aime']) echo ("_selected"); ?>.png" /></br>
                                </form>

                                <?php if ($commentaire['idUtilisateur'] == $_SESSION['USER_ID']) : ?>
                                    <!-- Bouton supprimer commentaire -->
                                    <form action="submit_recette_comment_deletion.php" method="POST" id="recette_deletion">
                                        <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                        <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                        <input type="image" id="del_comment_buttom" alt="delete comment" src="img/corbeille.png" /></br>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div id="recette_comment">
                            <p><?php echo ($commentaire['commentaire']); ?></p>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (isset($_SESSION['USER_LOGGED'])) : ?>
                <div id="recette_form_comment">
                    <form action="submit_recette_comment.php" method="POST" id="recette_create_comment">

                        <label for="commentaire">Déposer un commentaire</label>
                        <textarea rows="5" cols="75" required placeholder="Ecrivez ici..." name="commentaire" id="recette_create_comment_text" maxlength="255"></textarea>
                        <!--<input type="text" name="commentaire" required placeholder="Ecrivez ici..." id="recette_create_comment_text" />-->
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />

                        <div id="recette_comment_buttom_div">
                            <input type="submit" value="Envoyer le commentaire" id="comment_buttom" /></br>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>