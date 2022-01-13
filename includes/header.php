<div class="header_entier">
    <div id="header_logo">
        <a href="accueil.php"><img src="img/logo - 64x64.png" alt="logo de rady" /></a />
        <h1 id="h1header"><a href="accueil.php">Rady</a></h1>
    </div>


    <div class="searchbar">
        <form id="searchBar" method="POST" action="search.php">
            <input type="search" name="searchBar" id="searchbar" placeholder="Rechercher une recette..." />
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