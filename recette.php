<?php include_once('includes/init.php'); ?>
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
                <p>NOM</p>
            </div>

            <div id="recette_desc">
                <p>fefzsefzes zefezfzef zfzefzfze</p>
            </div>

            <div id="recette_note_like">
                <div id="recette_note">
                    <p> 10 </p>
                </div>

                <div id="recette_like">
                    <p> 26</p>
                </div>
            </div>

            <div id="recette_img">
                <p><img src="img/seoul_img.jpg" alt="image de la recette" /></p>
            </div>

            <div id="recette_ingrediants">
                <ul>
                    <li>zefzef</li>
                    <li>deze aee </li>
                    <li>zefzef</li>
                    <li>deze aee </li>
                </ul>
            </div>

            <div id="recette_etapes">
                <ol>
                    <li>zefzef</li>
                    <li>deze aee </li>
                    <li>zefzef</li>
                    <li>deze aee </li>
                </ol>
            </div>

            <div id="recette_comment">

            </div>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>