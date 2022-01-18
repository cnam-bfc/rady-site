<?php include_once('includes/init.php'); ?>

<?php
try {
    $sqlQuery = 'SELECT Recettes.id, Recettes.nom, Recettes.description, Recettes.visible, Recettes.idAuteur, COUNT(*) as popularity
    FROM Recettes, RecettesUtilisateurs
    WHERE Recettes.visible = 1 AND Recettes.id = RecettesUtilisateurs.idRecette AND (RecettesUtilisateurs.date between date_sub(now(),INTERVAL 1 WEEK) and now())
    GROUP BY id
    ORDER BY popularity DESC';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $recettes = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once('includes/head.php'); ?>
    <title>Accueil</title>
    <link rel="stylesheet" href="css/accueil.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="accueil_banniere">
            <video src="video/pub.mp4" controls poster="img/background_video.jpg" width="500"></video>
        </div>


        <div id="accueil_content_div">
            <!-- l'actualite du site -->
            <div id="accueil_actualites">

                <!-- ici il faudrait mettre une actualite (code en brut pour le moment) avec une petite image pour illustre
            et que la boite fasse la meme taille que celle de accueil top recette  -->

                <h1>Rady</h1>

                <div id="accueil_border_actu">
                    <h2>Le projet</h2>
                    <p>Rady est une solution pour aider les personnes qui souhaitent créer leurs produits ménagers eux-mêmes, 
                        en utilisant des ingrédients simples et naturels. Ce site permet à la communauté de noter les recettes, 
                        d’échanger leurs avis ou encore d’ajouter leur meilleure recette inratable et efficace. 
                        L’application de réalité virtuelle, proposée sur le casque HoloLens, vous permettra de suivre les recettes de 
                        manière ergonomique et surtout avec les mains libres ! Les animations et le guide des étapes vous aideront à réussir 
                        vos recettes du premier coup.</p>

                    <h2 class="accueil_title_h2">Eco-responsabilité</h2>
                    <p>Rady vous aide à diminuer vos déchets et votre impact environnemental. </br> </p>
                    <p>L’éco responsabilité désigne l’ensemble des actions visant à limiter les impacts sur 
                        l’environnement de l’activité quotidienne des collectivités. L’éco responsabilité passe 
                        par de nouveaux choix de gestion, d’achats, d’organisation du travail, des investissements et par la sensibilisation.</p>

                    <h2 class="accueil_title_h2">Qui sommes nous</h2>
                    <p>Nous sommes un groupe de 8 étudiants, motivés par l’utilisation des nouvelles technologies pour encourager les changements 
                        d’habitude. Nous avons développé ce projet pour le défi Chal’enge, porté par nos écoles : l’Institut Image Arts et Métiers, 
                        l’IUT de Chalon-sur-Saône et le CNAM.  </p>
                    <p>Lucas et Alexandra ont travaillés sur les modélisations 3D et les animations, Stanislas et Jérémie ont créés la charte graphique, 
                        les logos et la vidéo, Alban et Victor ont développé le site internet et la base de donnée, Jennifer et Eloïse ont développé l’application HoloLens 
                        et gérer le projet.</p> 
                </div>

            </div>

            <!-- les top recettes de la semaine -->
            <div id="accueil_top_recettes">

                <!-- ici il faudrait mettre un titre du genre " les 3 recettes de la semaine et en dessous
            3 blocs contenant chacun 1- le nom de la recette / 2- sa description / 3- sa note -->

                <div id="top_recettes_title">
                    <h1>Les recettes de la semaine</h1>
                    <p>Envie d'essayer ? cliquez sur une recette !</p>
                </div>

                <div id="top_recettes_border">
                    <?php $nbRecettes = 0; ?>
                    <?php foreach ($recettes as $recette) : ?>
                        <?php if ($nbRecettes >= 3) break; ?>
                        <?php $nbRecettes++;
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

                        // Si la recette a un auteur
                        if (isset($recette['idAuteur'])) {
                            // On récupère l'auteur de la recette
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
                        }
                        ?>

                        <a href=<?php echo ('recette.php?id=' . htmlspecialchars($recette['id'])); ?>>
                            <div class="top_recettes_border">

                                <h1><?php echo ($recette['nom']); ?></h1>
                                <p><?php echo ($recette['description']); ?></p>
                                <div id="accueil_top_recette_container">
                                    <?php if (isset($recette['idAuteur'])) : ?>
                                        <strong><?php echo ($utilisateur['pseudo']); ?></strong>
                                    <?php endif; ?>
                                    <em><?php echo (($nbLike - $nbDislike) . ' Like'); ?></em>
                                </div>

                            </div>
                        </a>
                        <br id='top_recettes_separator' />
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>