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
        include_once('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// La recette est dans le tableau $recette
foreach ($recettes as $recette) {
}

if ($recette['visible'] == 0 && (!isset($_SESSION['USER_LOGGED']) || $recette['idAuteur'] != $_SESSION['USER_ID'])) {
    $_SESSION['ERROR_MSG'] = 'Recette en préparation';
    include_once('includes/error.php');
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
    include_once('includes/error.php');
}

// On récupère les étapes de la recette
try {
    $sqlQuery = 'SELECT * FROM Etapes WHERE idRecette = :idRecette ORDER BY id ASC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
    $etapes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On récupère les ingrédients de la recette
try {
    $sqlQuery = 'SELECT * FROM Ingredients, IngredientsRecettes WHERE IngredientsRecettes.idRecette = :idRecette AND IngredientsRecettes.idIngredient = Ingredients.id ORDER BY Ingredients.nom';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
    $ingredients = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On récupère les ustensiles de la recette
try {
    $sqlQuery = 'SELECT * FROM Ustensiles, UstensilesRecettes WHERE UstensilesRecettes.idRecette = :idRecette AND UstensilesRecettes.idUstensile = Ustensiles.id ORDER BY Ustensiles.nom';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'idRecette' => $recette['id']
    ]);
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
    <title>Recette</title>
    <link rel="stylesheet" href="css/recette.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="recette_main">

            <!-- Nom de la recette -->
            <div id="recette_title">

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <form action="submit_recette_delete.php" method="POST" class="del_buttom">
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <input type="image" src="img/corbeille.png" alt="supprimer la recette" />
                    </form>
                <?php endif; ?>

                <h1><?php echo htmlspecialchars($recette['nom']); ?></h1>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <form action="recette_create.php" method="POST" class="edit_buttom">
                        <input type="hidden" name="edit" value="true" />
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <input type="image" src="img/edit.png" alt="éditer le titre" />
                    </form>
                <?php endif; ?>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <!-- Visibilité de la page -->
                    <form action="submit_recette_visibility.php" method="POST" id="recette_public">
                        <input type="hidden" name="id" value="<?php echo ($recette['id']); ?>" />
                        <input type="hidden" name="visibility" value="<?php if ($recette['visible'] == 0) echo ("true");
                                                                        elseif ($recette['visible'] == 1) echo ("false"); ?>" />
                        <input Type="image" src="img/eye<?php if ($recette['visible']) echo ("_selected"); ?>.png" alt="page privée / public" />
                    </form>
                <?php endif; ?>

            </div>

            <!-- Description de la recette -->
            <div id="recette_desc">
                <h3><?php echo htmlspecialchars($recette['description']); ?></h3>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <form action="recette_create.php" method="POST" class="edit_buttom">
                        <input type="hidden" name="edit" value="true" />
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <input Type="image" src="img/edit.png" alt="éditer la description" />
                    </form>
                <?php endif; ?>
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
                        include_once('includes/error.php');
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
                            include_once('includes/error.php');
                        }
                        ?>

                        <!-- Bouton like -->
                        <form action="submit_recette_like<?php if (count($likes) > 0 && $like['aime']) echo ("_delete"); ?>.php" method="POST" id="recette_like">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="hidden" name="aime" value="true" />
                            <input type="image" id="like_buttom" alt="like" src="img/like<?php if (count($likes) > 0 && $like['aime']) echo ("_selected"); ?>.png" /></br>
                        </form>

                        <!-- Bouton dislike -->
                        <form action="submit_recette_like<?php if (count($likes) > 0 && !$like['aime']) echo ("_delete"); ?>.php" method="POST" id="recette_dislike">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="hidden" name="aime" value="false" />
                            <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike<?php if (count($likes) > 0 && !$like['aime']) echo ("_selected"); ?>.png" /></br>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div id="recette_info_container">
                <?php if (!(!isset($recette['difficulte']) && !isset($recette['tempsPreparation']) && !isset($recette['tempsConservation']))) : ?>
                    <div id="recette_more_informations">
                        <?php if (isset($recette['difficulte'])) : ?>
                            <p>Difficulté </br><?php echo ($recette['difficulte']); ?></p>
                        <?php endif; ?>
                        <?php if (isset($recette['tempsPreparation'])) : ?>
                            <p>Temps de préparation</br><?php echo ($recette['tempsPreparation']); ?> minutes</p>
                        <?php endif; ?>
                        <?php if (isset($recette['tempsConservation'])) : ?>
                            <p>Temps de conservation</br><?php echo ($recette['tempsConservation']); ?> mois</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <?php if (!isset($recette['difficulte']) && !isset($recette['tempsPreparation']) && !isset($recette['tempsConservation'])) : ?>
                        <p>Ajouter des informations complémentaires</p>
                    <?php endif; ?>
                    <form action="recette_create.php" method="POST" class="edit_buttom">
                        <input type="hidden" name="edit" value="true" />
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <?php if (!isset($recette['difficulte']) && !isset($recette['tempsPreparation']) && !isset($recette['tempsConservation'])) : ?>
                            <input Type="image" src="img/add.png" alt="ajouter les informations" />
                        <?php else : ?>
                            <input Type="image" src="img/edit.png" alt="éditer les informations" />
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Image de la recette -->
            <div id="recette_img_container">
                <?php if ($recette['imageUrl'] == null && isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <p>Ajouter une image</p>
                <?php elseif ($recette['imageUrl'] != null) : ?>
                    <div id="recette_img">
                        <p><img src="<?php echo ($recette['imageUrl']); ?>" alt="image de la recette" /></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <form action="recette_create.php" method="POST" class="edit_buttom">
                        <input type="hidden" name="edit" value="true" />
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <?php if ($recette['imageUrl'] == null) : ?>
                            <input Type="image" src="img/add.png" alt="ajouter une image" />
                        <?php else : ?>
                            <input Type="image" src="img/edit.png" alt="éditer l'image" />
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Ingrédents de la recette -->
            <div id="recette_ingrediants">
                <h2>Ingrédients</h2>

                <?php foreach ($ingredients as $ingredient) : ?>
                    <div class="recette_ingredient_etape_container">

                        <div class="recette_ingredient_etape_container_desc">
                            <p><em>- </em><?php echo ('<strong>' . htmlspecialchars($ingredient['nom']) . '</strong>: ' . htmlspecialchars($ingredient['quantite']) . ' ' . htmlspecialchars($ingredient['unite'])); ?></p>
                        </div>

                        <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                            <div class="recette_ingredient_etape_container_buttom">
                                <form action="recette_ingredient.php" method="POST">
                                    <input type="hidden" name="edit" value="true" />
                                    <input type="hidden" name="recette" value="<?php echo ($ingredient['idRecette']); ?>" />
                                    <input type="hidden" name="ingredient" value="<?php echo ($ingredient['idIngredient']); ?>" />
                                    <input type="image" alt="bouton éditer ingredient" src="img/edit.png" />
                                </form>
                                <form action="submit_recette_ingredient_delete.php" method="POST">
                                    <input type="hidden" name="recette" value="<?php echo ($ingredient['idRecette']); ?>" />
                                    <input type="hidden" name="ingredient" value="<?php echo ($ingredient['idIngredient']); ?>" />
                                    <input type="image" alt="bouton supprimer ingrédient" src="img/corbeille.png" />
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <div class="recette_add">
                        <form action="recette_ingredient.php" method="POST">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="image" alt="bouton ajout ingrédient" src="img/add.png" />
                        </form>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Ustensiles de la recette -->
            <div id="recette_ustensiles">

                <h2>Ustensiles</h2>

                <?php foreach ($ustensiles as $ustensile) : ?>
                    <div class="recette_ingredient_etape_container">

                        <div class="recette_ingredient_etape_container_desc">
                            <p><em>- </em><?php echo ('<strong>' . htmlspecialchars($ustensile['nom']) . '</strong>: ' . htmlspecialchars($ustensile['quantite']) . ' ' . htmlspecialchars($ustensile['unite'])); ?></p>
                        </div>

                        <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                            <div class="recette_ingredient_etape_container_buttom">
                                <form action="recette_ustensile.php" method="POST">
                                    <input type="hidden" name="edit" value="true" />
                                    <input type="hidden" name="recette" value="<?php echo ($ustensile['idRecette']); ?>" />
                                    <input type="hidden" name="ustensile" value="<?php echo ($ustensile['idUstensile']); ?>" />
                                    <input type="image" alt="bouton éditer ustensile" src="img/edit.png" />
                                </form>
                                <form action="submit_recette_ustensile_delete.php" method="POST">
                                    <input type="hidden" name="recette" value="<?php echo ($ustensile['idRecette']); ?>" />
                                    <input type="hidden" name="ustensile" value="<?php echo ($ustensile['idUstensile']); ?>" />
                                    <input type="image" alt="bouton supprimer ustensile" src="img/corbeille.png" />
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <div class="recette_add">
                        <form action="recette_ustensile.php" method="POST">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="image" alt="bouton ajout ustensile" src="img/add.png" />
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Étapes de la recette -->
            <div id="recette_etapes">
                <h2>Étapes</h2>
                <?php foreach ($etapes as $etape) : ?>
                    <div class="recette_ingredient_etape_container">
                        <div class="recette_ingredient_etape_container_num">
                            <p><?php echo htmlspecialchars($etape['id']); ?></p>
                        </div>

                        <div class="recette_ingredient_etape_container_desc">
                            <p><?php echo htmlspecialchars($etape['description']); ?></p>
                        </div>

                        <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                            <div class="recette_ingredient_etape_container_buttom">
                                <form action="recette_etape.php" method="POST">
                                    <input type="hidden" name="edit" value="true" />
                                    <input type="hidden" name="recette" value="<?php echo ($etape['idRecette']); ?>" />
                                    <input type="hidden" name="etape" value="<?php echo ($etape['id']); ?>" />
                                    <input type="image" alt="bouton éditer étape" src="img/edit.png" />
                                </form>
                                <form action="submit_recette_etape_delete.php" method="POST">
                                    <input type="hidden" name="recette" value="<?php echo ($etape['idRecette']); ?>" />
                                    <input type="hidden" name="etape" value="<?php echo ($etape['id']); ?>" />
                                    <input type="image" alt="bouton supprimer étape" src="img/corbeille.png" />
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['USER_LOGGED']) && $recette['idAuteur'] == $_SESSION['USER_ID']) : ?>
                    <div class="recette_add">
                        <form action="recette_etape.php" method="POST">
                            <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                            <input type="image" alt="bouton ajout étape" src="img/add.png" />
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Auteur de la recette -->
            <div id="recette_author">
                <p>
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
                            include_once('includes/error.php');
                        }

                        foreach ($utilisateurs as $utilisateur) {
                        }
                        ?>
                        <?php echo ('Auteur: ' . htmlspecialchars($utilisateur['pseudo'])); ?>
                    <?php endif; ?>
                </p>
                <p><?php echo ('Numéro de recette : ' . ($recette['id'])); ?> </p>
            </div>

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
                        include_once('includes/error.php');
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
                                include_once('includes/error.php');
                            }
                            ?>
                            <p><?php echo htmlspecialchars(($nbLike - $nbDislike) . ' Like'); ?></p>

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
                                    include_once('includes/error.php');
                                }
                                ?>

                                <!-- Bouton liker commentaire -->
                                <form action="submit_recette_comment_like<?php if (count($likes) > 0 && $like['aime']) echo ("_delete"); ?>.php" method="POST" id="recette_like">
                                    <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                    <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                    <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                    <input type="hidden" name="aime" value="true" />
                                    <input type="image" id="like_buttom" alt="like" src="img/like<?php if (count($likes) > 0 && $like['aime']) echo ("_selected"); ?>.png" /></br>
                                </form>

                                <!-- Bouton disliker commentaire -->
                                <form action="submit_recette_comment_like<?php if (count($likes) > 0 && !$like['aime']) echo ("_delete"); ?>.php" method="POST" id="recette_dislike">
                                    <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                    <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                    <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                    <input type="hidden" name="aime" value="false" />
                                    <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike<?php if (count($likes) > 0 && !$like['aime']) echo ("_selected"); ?>.png" /></br>
                                </form>

                                <?php if ($commentaire['idUtilisateur'] == $_SESSION['USER_ID']) : ?>
                                    <!-- Bouton supprimer commentaire -->
                                    <form action="submit_recette_comment_delete.php" method="POST" id="recette_deletion">
                                        <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                        <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                        <input type="image" id="del_comment_buttom" alt="delete comment" src="img/corbeille.png" /></br>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div id="recette_comment">
                            <p><?php echo htmlspecialchars($commentaire['commentaire']); ?></p>
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