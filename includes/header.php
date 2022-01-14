<div id="header_main_div">
    <div id="header_logo">
        <a href="accueil.php"><img src="img/logo - 64x64.png" alt="Logo de Rady" /></a />
        <a href="accueil.php"><h1>Rady</h1></a>
    </div>


    <div>
        <form method="GET" action="search.php">
            <input type="search" name="search" id="header_searchbar" placeholder="Rechercher une recette..." />
            <!-- <button type="submit"><i class="fa fa-search"></i></button> -->
        </form>
    </div>

    <nav>
        <ul id="header_menu">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <?php
            $connecte = false;
            if ($connecte) {
                echo '<li><a href="logout.php">Déconnexion</a></li>';
            } else {
                echo '<li><a href="login.php">Connexion</a></li>';
                echo '<li><a href="register.php">Inscription</a></li>';
            }
            ?>
        </ul>

    </nav>
</div>