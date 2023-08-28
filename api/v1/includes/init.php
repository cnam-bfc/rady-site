<?php
// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';charset=utf8', $_ENV['MYSQL_USERNAME'], $_ENV['MYSQL_PASSWORD']);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur de connexion à la base de données:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

$separator = '_____';
function displayKeyValue(array $array, string $key, string $separator, bool $endSeparator): string
{
    /*return ($key . '$' . $array[$key] . '$');*/
    if (isset($array[$key]))
        if ($endSeparator)
            return ($key . $separator . $array[$key] . $separator);
        else
            return ($key . $separator . $array[$key]);
    elseif ($endSeparator)
        return ($key . $separator . 'Indéterminé' . $separator);
    else
        return ($key . $separator . 'Indéterminé');
}
