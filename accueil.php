<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Accueil</title>
    <link rel="stylesheet" href="css/accueil.css" />
</head>

<body>
    <div class="main_div">
        <header id="accueil_header"><?php include('includes/header.php'); ?></header>
        <div id="accueil_banniere">
            <video src="img/Seoul.mp4" controls poster="img/seoul_img.jpg" width="500"></video>
        </div>

        <div id="accueil_content_div">
            <!-- l'actualite du site -->
            <div id="accueil_actualites">

                <!-- ici il faudrait mettre une actualite (code en brut pour le moment) avec une petite image pour illustre
            et que la boite fasse la meme taille que celle de accueil top recette  -->

            </div>

            <!-- les top recettes de la semaine -->
            <div id="accueil_top_recettes">

                <!-- ici il faudrait mettre un titre du genre " les 3 recettes de la semaine et en dessous
            3 blocs contenant chacun 1- le nom de la recette / 2- sa description / 3- sa note -->

            </div>
        </div>
    </div>

    <footer id="accueil_footer"><?php include('includes/footer.php'); ?></footer>
</body>

</html>