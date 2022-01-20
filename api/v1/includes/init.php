<?php
// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=10.254.1.3;dbname=rady;charset=utf8', 'rady', 'yWEFf3ioTM9BHmAa');
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
