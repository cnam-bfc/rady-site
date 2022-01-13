<?php
if (
    !isset($_POST['searchBar'])
    || empty($_POST['searchBar'])
) {
    include('includes/error.php');

    return;
}

$search = htmlspecialchars($_POST['searchBar']);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Recherche</title>
</head>

<body>
    <header><?php include_once('includes/header.php'); ?></header>

    <div>
        <h1>Résultats de la recherche de <strong><?php echo $search; ?></strong></h1>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>