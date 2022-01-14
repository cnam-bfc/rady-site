<?php include_once('includes/init.php'); ?>

<?php
if (isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}
?>

<?php
if (
    !isset($_POST['email'])
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    || !isset($_POST['pseudo'])
    || empty($_POST['pseudo'])
    || str_contains($_POST['pseudo'], '@')
    || !isset($_POST['nom'])
    || empty($_POST['nom'])
    || !isset($_POST['prenom'])
    || empty($_POST['prenom'])
    || !isset($_POST['password'])
    || empty($_POST['password'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$email = htmlspecialchars($_POST['email']);
$pseudo = htmlspecialchars($_POST['pseudo']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$password = htmlspecialchars($_POST['password']);

// On vérifie si il existe déjà un utilisateur avec ce pseudo
try {
    $sqlQuery = 'SELECT pseudo FROM Utilisateurs WHERE pseudo = :pseudo';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'pseudo' => $pseudo
    ]);
    $pseudos = $sqlStatement->fetchAll();
    if (count($pseudos) > 0) {
        $_SESSION['ERROR_MSG'] = "Le pseudo \"" . $pseudo . "\" existe déjà";
        include('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On vérifie si il existe déjà un utilisateur avec cet email
try {
    $sqlQuery = 'SELECT email FROM Utilisateurs WHERE email = :email';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'email' => $email
    ]);
    $emails = $sqlStatement->fetchAll();
    if (count($emails) > 0) {
        $_SESSION['ERROR_MSG'] = "L'email \"" . $email . "\" existe déjà";
        include('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// Tout est OK on ajoute l'utilisateur
try {
    $sqlQuery = 'INSERT INTO Utilisateurs (pseudo, email, hashPassword, prenom, nom) VALUES (:pseudo, :email, :hashPassword, :prenom, :nom)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'pseudo' => $pseudo,
        'email' => $email,
        'nom' => $nom,
        'prenom' => $prenom,
        'hashPassword' => password_hash($password, PASSWORD_BCRYPT)
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include('includes/error.php');
}

// On sauvegarde les informations de l'utilisateur dans la session
// TODO Renvoyer sur la page submit_login.php pour enregistrer ces infos à un seul endroit et pas avoir 2 versions du code
$_SESSION['USER_LOGGED'] = true;
$_SESSION['USER_PSEUDO'] = $pseudo;
$_SESSION['USER_EMAIL'] = $email;
$_SESSION['USER_NOM'] = $nom;
$_SESSION['USER_PRENOM'] = $prenom;

// Retour en arrière
include_once('includes/redirect_backward.php');
?>