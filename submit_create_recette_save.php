<?php include_once('includes/init.php'); ?>

<?php
if (
    !isset($_SESSION['USER_LOGGED'])
    || !isset($_SESSION['RECETTE_CREATE'])
) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['key'])
    || empty($_POST['key'])
    || !isset($_POST['value'])
    || empty($_POST['value'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$key = htmlspecialchars($_POST['key']);
$value = htmlspecialchars($_POST['value']);
$id = 'RECETTE_CREATE_' . $_SESSION['RECETTE_CREATE_ID'] . '_' . $key;

$_SESSION[$id] = $value;

$_SESSION['REFRESH_PAGE'] = 2;
include_once('includes/redirect_backward.php');
