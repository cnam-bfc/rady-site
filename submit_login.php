<?php include_once('includes/init.php'); ?>

<?php
if (isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}

if (
    !isset($_POST['identifiant'])
    || empty($_POST['identifiant'])
    || (strpos($_POST['identifiant'], '@') && !filter_var($_POST['identifiant'], FILTER_VALIDATE_EMAIL))
    || !isset($_POST['password'])
    || empty($_POST['password'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$identifiant = htmlspecialchars($_POST['identifiant']);
$password = htmlspecialchars($_POST['password']);

// On récupère l'utilisateur dans la base de données
try {
    $sqlQuery = 'SELECT * FROM Utilisateurs WHERE pseudo = :identifiant OR email = :identifiant';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'identifiant' => $identifiant
    ]);
    $utilisateurs = $sqlStatement->fetchAll();
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

foreach ($utilisateurs as $utilisateur) {
    if (!password_verify($password, $utilisateur['hashPassword'])) {
        continue;
    }
    $id = htmlspecialchars($utilisateur['id']);
    $pseudo = htmlspecialchars($utilisateur['pseudo']);
    $email = htmlspecialchars($utilisateur['email']);
    $nom = htmlspecialchars($utilisateur['nom']);
    $prenom = htmlspecialchars($utilisateur['prenom']);
}

if (!isset($pseudo)) {
    $_SESSION['ERROR_MSG'] = 'Identifiant ou mot de passe incorrect';
    include_once('includes/error.php');
}

// On sauvegarde les informations de l'utilisateur dans la session
$_SESSION['USER_LOGGED'] = true;
$_SESSION['USER_ID'] = $id;
$_SESSION['USER_PSEUDO'] = $pseudo;
$_SESSION['USER_EMAIL'] = $email;
$_SESSION['USER_NOM'] = $nom;
$_SESSION['USER_PRENOM'] = $prenom;

include_once('includes/redirect_backward.php');
?>