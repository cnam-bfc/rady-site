<?php include_once('includes/init.php'); ?>

<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    include_once('includes/redirect_backward.php');
}

$id = htmlspecialchars($_GET['id']);

try {
    $sqlQuery = 'SELECT * FROM Recettes WHERE id = :id';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'id' => $id
    ]);
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

if (count($recettes) == 0) {
    $_SESSION['ERROR_MSG'] = 'Recette introuvable';
    include('includes/error.php');
}

foreach ($recettes as $recette) {
}

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
            <div id="recette_title">
                <h1><?php echo htmlspecialchars($recette['nom']); ?></h1>
            </div>

            <div id="recette_desc">
                <h3><?php echo htmlspecialchars($recette['description']); ?></h3>
            </div>

            <div id="recette_note_like">
                <div id="recette_note_avg">

                    <p id="recette_note">Je suis la note en étoile </p>
                    <p id="recette_avg">Je suis la moyenne</p>

                </div>

                <div id="recette_like_dislike">
                    <?php
                    if (isset($_SESSION['USER_LOGGED'])) {
                        // On check si l'utilisateur a aimé ou pas
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
                    }
                    ?>
                    <form action="submit_recette_like.php" method="POST" id="recette_like">
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <input type="hidden" name="aime" value="true" />
                        <input type="image" id="like_buttom" alt="like" src="img/like.png" <?php
                                                                                            if (!isset($_SESSION['USER_LOGGED'])) {
                                                                                                echo ("disabled");
                                                                                            } elseif (count($likes) > 0 && $like['aime']) {
                                                                                                echo ("class=\"recette_like_selected\"");
                                                                                            }
                                                                                            ?> /></br>
                    </form>

                    <form action="submit_recette_like.php" method="POST" id="recette_dislike">
                        <input type="hidden" name="recette" value="<?php echo ($recette['id']); ?>" />
                        <input type="hidden" name="aime" value="false" />
                        <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike.png" <?php
                                                                                                    if (!isset($_SESSION['USER_LOGGED'])) {
                                                                                                        echo ("disabled");
                                                                                                    } elseif (count($likes) > 0 && !$like['aime']) {
                                                                                                        echo ("class=\"recette_dislike_selected\"");
                                                                                                    }
                                                                                                    ?> /></br>
                    </form>

                </div>
            </div>

            <div id="recette_img">
                <p><img src="img/liquide_vaisselle.jpg" alt="image de la recette" /></p>
            </div>

            <div id="recette_ingrediants">

                <h2>Ingrédiants</h2>

                <ul>
                    <li>zefzef</li>
                    <li>deze aee </li>
                    <li>zefzef</li>
                    <li>deze aee </li>
                </ul>

            </div>

            <div id="recette_etapes">
                <h2>Etapes</h2>
                <ol>
                    <li>zefzef</li>
                    <li>deze aee </li>
                    <li>zefzef</li>
                    <li>deze aee </li>
                </ol>
            </div>

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

            <h1 id="recette_comment_title">Commentaires</h1>
            <div id="recette_container_comment_main">
            <?php foreach ($commentaires as $commentaire) : ?>
                    <div id="recette_container_comment">
                        <div id="recette_container_name_like">
                            <em><?php
                                try {
                                    $sqlQuery = 'SELECT * FROM Utilisateurs WHERE id = :id';
                                    $sqlStatement = $mysqlClient->prepare($sqlQuery);
                                    $sqlStatement->execute([
                                        'id' => $commentaire['idUtilisateur']
                                    ]);
                                    $utilisateurs = $sqlStatement->fetchAll();
                                } catch (Exception $e) {
                                    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
                                    include('includes/error.php');
                                }

                                foreach ($utilisateurs as $utilisateur) {
                                }

                                echo htmlspecialchars($utilisateur['pseudo']);
                                ?></em>
                            <?php
                            if (isset($_SESSION['USER_LOGGED'])) {
                                // On check si l'utilisateur a aimé ou pas
                                try {
                                    $sqlQuery = 'SELECT * FROM LikesCommentaires WHERE idRecette = :idRecette AND idUtilisateur = :idUtilisateur AND dateCommentaire = :dateCommentaire AND idAuteurCommentaire = :idAuteurCommentaire ORDER BY aime desc';
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
                            }
                            ?>
                            <p>NB like</p>
                            <form action="submit_comment_like.php" method="POST" id="recette_like">
                                <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                <input type="hidden" name="aime" value="true" />
                                <input type="image" id="like_buttom" alt="like" src="img/like.png" <?php
                                                                                                    if (!isset($_SESSION['USER_LOGGED'])) {
                                                                                                        echo ("disabled");
                                                                                                    } elseif (count($likes) > 0 && $like['aime']) {
                                                                                                        echo ("class=\"recette_like_selected\"");
                                                                                                    }
                                                                                                    ?> /></br>
                            </form>

                            <form action="submit_comment_like.php" method="POST" id="recette_dislike">
                                <input type="hidden" name="date" value="<?php echo ($commentaire['date']); ?>" />
                                <input type="hidden" name="recette" value="<?php echo ($commentaire['idRecette']); ?>" />
                                <input type="hidden" name="utilisateur" value="<?php echo ($commentaire['idUtilisateur']); ?>" />
                                <input type="hidden" name="aime" value="false" />
                                <input type="image" id="dislike_buttom" alt="dislike" src="img/dislike.png" <?php
                                                                                                            if (!isset($_SESSION['USER_LOGGED'])) {
                                                                                                                echo ("disabled");
                                                                                                            } elseif (count($likes) > 0 && !$like['aime']) {
                                                                                                                echo ("class=\"recette_dislike_selected\"");
                                                                                                            }
                                                                                                            ?> /></br>
                            </form>

                            <div id="recette_del_comment">
                                <!-- trouver une image de corbeille pour supp un comment SI on est le proprio -->
                                <input type="image" id="del_comment_buttom" alt="delete comment" src="img/logo - 64x64.png" />
                            </div>

                        </div>

                        <div id="recette_comment">
                            <p><?php echo ($commentaire['commentaire']); ?></p>
                        </div>

                    </div>
            <?php endforeach; ?>
            </div>

            <?php if (isset($_SESSION['USER_LOGGED'])) : ?>
                <div id="recette_form_comment">
                    <form action="submit_comment.php" method="POST" id="recette_create_comment">

                        <label for="commentaire">Déposer un commentaire</label>
                        <textarea rows="5" cols="75" required placeholder="Ecrivez ici..." id="recette_create_comment_text" maxlength="255"></textarea>
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