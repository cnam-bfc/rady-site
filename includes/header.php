<div id="header_main_div">
    <div id="header_logo">
        <a href="./">
            <img src="img/logo - 64x64.png" alt="Logo de Rady" />
        </a>
        <a href="./">
            <h1>Rady</h1>
        </a>
    </div>

    <div id="header_search_div">
        <form method="GET" action="recettes.php">
            <input type="search" name="search" id="header_searchbar" <?php if (isset($header_searchbar_focus) && $header_searchbar_focus) echo ('autofocus'); ?> placeholder="Rechercher une recette..." />
            <!-- <button type="submit"><i class="fa fa-search"></i></button> -->
        </form>
    </div>

    <nav>
        <ul id="header_menu">
            <li><a href="./">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <li id="header_menu_separator">|</li>
            <?php if (!isset($_SESSION['USER_LOGGED'])) : ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php else : ?>
                <li><a href="account.php"><?php echo htmlspecialchars($_SESSION['USER_PRENOM']) . ' ' . htmlspecialchars($_SESSION['USER_NOM']); ?></a></li>
                <li><a href="submit_logout.php">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>