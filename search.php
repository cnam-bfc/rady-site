<?php
if (
    !isset($_GET['search'])
    || empty($_GET['search'])
) {
    include('includes/error.php');

    return;
}

$search = htmlspecialchars($_GET['search']);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include('includes/head.php'); ?>
    <title>Recherche</title>
</head>

<body>
    <div class="main_div">
        <header><?php include_once('includes/header.php'); ?></header>

        <div>
            <h1>Résultats de la recherche de "<?php echo $search; ?>":</h1>
        </div>
    </div>

    <footer><?php include_once('includes/footer.php'); ?></footer>
</body>

</html>