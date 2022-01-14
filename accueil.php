<?php include_once('includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Accueil</title>
    <link rel="stylesheet" href="css/accueil.css" />
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div id="accueil_banniere">
            <video src="img/Seoul.mp4" controls poster="img/seoul_img.jpg" width="500"></video>
        </div>

        <div id="accueil_content_div">
            <!-- l'actualite du site -->
            <div id="accueil_actualites">

                <!-- ici il faudrait mettre une actualite (code en brut pour le moment) avec une petite image pour illustre
            et que la boite fasse la meme taille que celle de accueil top recette  -->

                <h1>Titre du texte</h1>

                <p id="accueil_actu_img"><img src="img/radis.png" alt="image de radis" /> </p>

                <h2>Sous-titre</h2>
                <p>Harum trium sententiarum nulli prorsus assentior. Nec enim illa prima vera est, ut, quem ad modum in se quisque sit,
                    sic in amicum sit animatus. Quam multa enim, quae nostra causa numquam faceremus, facimus causa amicorum! precari ab
                    indigno, supplicare, tum acerbius in aliquem invehi insectarique vehementius, quae in nostris rebus non satis honeste,
                    in amicorum fiunt honestissime; multaeque res sunt in quibus de suis commodis viri boni multa detrahunt detrahique patiuntur,
                    ut iis amici potius quam ipsi fruantur. </p>

                <h2>Sous-titre</h2>
                <p>Harum trium sententiarum nulli prorsus assentior. Nec enim illa prima vera est, ut, quem ad modum in se quisque sit,
                    sic in amicum sit animatus. Quam multa enim, quae nostra causa numquam faceremus, facimus causa amicorum! precari ab
                    indigno, supplicare, tum acerbius in aliquem invehi insectarique vehementius, quae in nostris rebus non satis honeste,
                    in amicorum fiunt honestissime; multaeque res sunt in quibus de suis commodis viri boni multa detrahunt detrahique patiuntur,
                    ut iis amici potius quam ipsi fruantur. </p>

                <h2>Sous-titre</h2>
                <p>Harum trium sententiarum nulli prorsus assentior. Nec enim illa prima vera est, ut, quem ad modum in se quisque sit,
                    sic in amicum sit animatus. Quam multa enim, quae nostra causa numquam faceremus, facimus causa amicorum! precari ab
                    indigno, supplicare, tum acerbius in aliquem invehi insectarique vehementius, quae in nostris rebus non satis honeste,
                    in amicorum fiunt honestissime; multaeque res sunt in quibus de suis commodis viri boni multa detrahunt detrahique patiuntur,
                    ut iis amici potius quam ipsi fruantur. </p>

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

                    <div class="top_recettes_border">

                        <h1>NOM</h1>
                        <p>Description esfsfe ses fsf sf sf sef sf sef sf sef zefa edf efh zihf zurhf iuhrf uheriuh eiurhf herf uheriu hieur
                            fezo ozeifj efi zjf </p>
                        <em>Note</em>

                    </div>

                    <div class="top_recettes_border">

                        <h1>NOM</h1>
                        <p>Description esfsfe ses fsf sf sf sef sf sef sf sef zefa edf efh zihf zurhf iuhrf uheriuh eiurhf herf uheriu hieur
                            fezo ozeifj efi zjf zoeifj zoie f zeif jzoei zejfoijz ef zoiejf izj iefj zoief jzeif jzoijef oijze if</p>
                        <em>Note</em>

                    </div>

                    <div class="top_recettes_border">

                        <h1>NOM</h1>
                        <p>Description esfsfe ses fsf sf sf sef sf sef sf sef zefa edf efh zihf zurhf iuhrf uheriuh eiurhf herf uheriu hieur
                            fezo ozeifj efi zjf zoeifj zoie f zeif jzoei zejfoijz ef zoiejf izj iefj zoief jzeif jzoijef oijze if</p>
                        <em>Note</em>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>