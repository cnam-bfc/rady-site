<?php
include_once('init.php');

try {
    $sqlQuery = 'SELECT COUNT(*) as nb FROM Recettes WHERE visible = 1';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute();
    $stats = $sqlStatement->fetchAll();
} catch (Exception $e) {
    echo ("ERREUR: SQL: " . $e->getMessage());
    die();
}

foreach ($stats as $stat) {
}

echo ('recettes' . $separator);
echo ($stat['nb']);
